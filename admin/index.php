<?php

require dirname(__DIR__) . '/includes/auth.php';
require dirname(__DIR__) . '/includes/admin-layout.php';
cs_require_admin();

$messages = cs_load_json('messages.json', []);
$recipes = cs_load_json('recipes.json', []);

cs_admin_header('Inbox');
?>
						<header class="major">
							<h1>Inbox</h1>
						</header>
						<p><?php echo count($messages); ?> messages · <?php echo count($recipes); ?> recipes.</p>
						<ul class="actions">
							<li><a href="/admin/recipes.php" class="button">Manage recipes</a></li>
							<li><a href="/admin/recipe-edit.php" class="button primary">Add a recipe</a></li>
						</ul>
						<?php if (!$messages): ?>
							<p>No enquiries yet.</p>
						<?php else: ?>
							<?php foreach ($messages as $msg): ?>
								<blockquote class="review">
									<p><?php echo nl2br(cs_h($msg['message'] ?? '')); ?></p>
									<cite><?php echo cs_h($msg['name'] ?? ''); ?> · <?php echo cs_h($msg['email'] ?? ''); ?> · <?php echo cs_h($msg['received_at'] ?? ''); ?></cite>
								</blockquote>
							<?php endforeach; ?>
						<?php endif; ?>
<?php
cs_admin_footer();
