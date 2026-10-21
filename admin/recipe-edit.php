<?php

require dirname(__DIR__) . '/includes/auth.php';
require dirname(__DIR__) . '/includes/admin-layout.php';
cs_require_admin();

$recipes = cs_load_json('recipes.json', []);
$id = (string) ($_GET['id'] ?? $_POST['id'] ?? '');
if ($id !== '' && !cs_valid_slug($id)) {
    $id = '';
}
$current = [
    'id' => '',
    'title' => '',
    'image' => '',
    'ingredients' => [],
    'icing' => [],
    'serve' => '',
    'method' => [],
];

foreach ($recipes as $recipe) {
    if (($recipe['id'] ?? '') === $id) {
        $current = array_merge($current, $recipe);
        break;
    }
}

$error = '';

if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST') {
    if (!cs_same_site_request() || !cs_csrf_ok()) {
        $error = 'Please try saving again.';
    } else {
        $title = trim((string) ($_POST['title'] ?? ''));
        if ($title === '' || strlen($title) > 120) {
            $error = 'A title is required (120 characters or fewer).';
        } else {
            $slug = $id !== '' ? $id : cs_slug($title);
            $image = cs_safe_image_src((string) ($current['image'] ?? ''));
            $image = $image !== '' ? ltrim($image, '/') : '';
            $file = $_FILES['image'] ?? [];
            $uploadName = (string) ($file['name'] ?? '');
            $uploadError = (int) ($file['error'] ?? UPLOAD_ERR_NO_FILE);

            if ($uploadName !== '' && $uploadError !== UPLOAD_ERR_NO_FILE) {
                if ($uploadError !== UPLOAD_ERR_OK) {
                    $error = match ($uploadError) {
                        UPLOAD_ERR_INI_SIZE, UPLOAD_ERR_FORM_SIZE => 'That photo is too large for the server. Try a smaller JPEG, PNG, or WebP.',
                        default => 'The photo did not upload. Try again with a JPEG, PNG, or WebP.',
                    };
                } else {
                    $tmp = (string) ($file['tmp_name'] ?? '');
                    $info = @getimagesize($tmp);
                    $allowed = [
                        IMAGETYPE_JPEG => 'jpg',
                        IMAGETYPE_PNG => 'png',
                        IMAGETYPE_WEBP => 'webp',
                    ];
                    if ($info === false || !isset($allowed[$info[2]])) {
                        $error = 'Please upload a JPEG, PNG, or WebP image.';
                    } else {
                        $filename = $slug . '-' . bin2hex(random_bytes(4)) . '.' . $allowed[$info[2]];
                        $stored = cs_store_upload_image($tmp, $filename, $info[2]);
                        if ($stored === null) {
                            $error = 'Could not save the image. Check that images/recipes is writable.';
                        } else {
                            $image = $stored;
                        }
                    }
                }
            }

            if ($error === '') {
                $entry = [
                    'id' => $slug,
                    'title' => substr($title, 0, 120),
                    'image' => $image,
                    'ingredients' => cs_lines_to_list((string) ($_POST['ingredients'] ?? '')),
                    'icing' => cs_lines_to_list((string) ($_POST['icing'] ?? '')),
                    'serve' => substr(trim((string) ($_POST['serve'] ?? '')), 0, 300),
                    'method' => cs_lines_to_list((string) ($_POST['method'] ?? ''), 40, 800),
                ];

                $found = false;
                foreach ($recipes as $i => $recipe) {
                    if (($recipe['id'] ?? '') === $slug) {
                        $recipes[$i] = $entry;
                        $found = true;
                        break;
                    }
                }
                if (!$found) {
                    $recipes[] = $entry;
                }
                cs_save_json('recipes.json', $recipes);
                cs_redirect('/admin/recipes.php?saved=1');
            }
        }

        $current = array_merge($current, [
            'title' => $title,
            'ingredients' => cs_lines_to_list((string) ($_POST['ingredients'] ?? '')),
            'icing' => cs_lines_to_list((string) ($_POST['icing'] ?? '')),
            'serve' => trim((string) ($_POST['serve'] ?? '')),
            'method' => cs_lines_to_list((string) ($_POST['method'] ?? ''), 40, 800),
        ]);
    }
}

$heading = ($current['id'] ?? '') !== '' ? 'Edit recipe' : 'Add recipe';
$photo = cs_safe_image_src((string) ($current['image'] ?? ''));
cs_admin_header($heading);
?>
						<header class="major">
							<h1><?php echo cs_h($heading); ?></h1>
						</header>
						<?php if ($error !== ''): ?>
							<p class="form-note error"><?php echo cs_h($error); ?></p>
						<?php endif; ?>
						<form method="post" action="/admin/recipe-edit.php" enctype="multipart/form-data" accept-charset="UTF-8">
							<?php echo cs_csrf_field(); ?>
							<input type="hidden" name="id" value="<?php echo cs_h($current['id'] ?? ''); ?>" />
							<div class="fields">
								<div class="field">
									<label for="title">Title</label>
									<input type="text" name="title" id="title" required maxlength="120" value="<?php echo cs_h($current['title'] ?? ''); ?>" />
								</div>
								<div class="field">
									<label for="image">Photo</label>
									<?php if ($photo !== ''): ?>
										<p>Current photo</p>
										<img src="<?php echo cs_h($photo); ?>" alt="" width="240" />
									<?php endif; ?>
									<input type="file" name="image" id="image" accept="image/jpeg,image/png,image/webp" />
								</div>
								<div class="field">
									<label for="ingredients">Ingredients (one per line)</label>
									<textarea name="ingredients" id="ingredients" rows="8" maxlength="8000"><?php echo cs_h(implode("\n", $current['ingredients'] ?? [])); ?></textarea>
								</div>
								<div class="field">
									<label for="icing">Icing / extra list (optional, one per line)</label>
									<textarea name="icing" id="icing" rows="4" maxlength="4000"><?php echo cs_h(implode("\n", $current['icing'] ?? [])); ?></textarea>
								</div>
								<div class="field">
									<label for="serve">To serve (optional)</label>
									<input type="text" name="serve" id="serve" maxlength="300" value="<?php echo cs_h($current['serve'] ?? ''); ?>" />
								</div>
								<div class="field">
									<label for="method">Method (one step per line)</label>
									<textarea name="method" id="method" rows="8" maxlength="12000"><?php echo cs_h(implode("\n", $current['method'] ?? [])); ?></textarea>
								</div>
							</div>
							<ul class="actions">
								<li><input type="submit" value="Save recipe" class="primary" /></li>
								<li><a href="/admin/recipes.php" class="button">Cancel</a></li>
							</ul>
						</form>
<?php
cs_admin_footer();
