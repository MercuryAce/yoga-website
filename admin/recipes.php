<?php

require dirname(__DIR__) . '/includes/auth.php';
require dirname(__DIR__) . '/includes/admin-layout.php';
cs_require_admin();

$recipes = cs_load_json('recipes.json', []);

if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST' && ($_POST['action'] ?? '') === 'delete') {
    if (!cs_same_site_request() || !cs_csrf_ok()) {
        cs_redirect('/admin/recipes.php');
    }
    $id = (string) ($_POST['id'] ?? '');
    if (cs_valid_slug($id)) {
        $kept = [];
        foreach ($recipes as $recipe) {
            if (($recipe['id'] ?? '') === $id) {
                $image = cs_safe_image_src((string) ($recipe['image'] ?? ''));
                if (str_starts_with($image, '/images/recipes/')) {
                    $file = dirname(__DIR__) . $image;
                    $root = realpath(dirname(__DIR__) . '/images/recipes');
                    $real = realpath($file);
                    if ($root && $real && str_starts_with($real, $root . DIRECTORY_SEPARATOR) && is_file($real)) {
                        unlink($real);
                    }
                }
                continue;
            }
            $kept[] = $recipe;
        }
        $recipes = array_values($kept);
        cs_save_json('recipes.json', $recipes);
    }
    cs_redirect('/admin/recipes.php?deleted=1');
}

cs_admin_header('Recipes');
?>
						<header class="major">
							<h1>Recipes</h1>
						</header>
						<ul class="actions">
							<li><a href="/admin/recipe-edit.php" class="button primary">Add recipe</a></li>
							<li><a href="/vegan.php" class="button">View kitchen</a></li>
						</ul>
						<?php if (!$recipes): ?>
							<p>No recipes yet.</p>
						<?php else: ?>
							<table class="admin-table">
								<thead>
									<tr>
										<th>Title</th>
										<th></th>
									</tr>
								</thead>
								<tbody>
									<?php foreach ($recipes as $recipe): ?>
										<tr>
											<td><?php echo cs_h($recipe['title'] ?? ''); ?></td>
											<td class="admin-actions">
												<a href="/admin/recipe-edit.php?id=<?php echo urlencode((string) ($recipe['id'] ?? '')); ?>" class="button small">Edit</a>
												<form method="post" action="/admin/recipes.php" data-confirm="Delete this recipe?">
													<?php echo cs_csrf_field(); ?>
													<input type="hidden" name="action" value="delete" />
													<input type="hidden" name="id" value="<?php echo cs_h($recipe['id'] ?? ''); ?>" />
													<input type="submit" value="Delete" />
												</form>
											</td>
										</tr>
									<?php endforeach; ?>
								</tbody>
							</table>
						<?php endif; ?>
<?php
cs_admin_footer();
