<?php
require __DIR__ . '/includes/layout.php';
$recipes = cs_load_json('recipes.json', []);
$page_title = 'Vegan recipes from the Creative Space kitchen | Shoreditch';
$page_description = 'Plant-based recipes from Vinay Soraya’s Creative Space kitchen in Shoreditch — vegan curry, shepherd’s pie, banana cupcakes, and more.';
cs_layout_start([
    'path' => 'vegan.php',
    'title' => $page_title,
    'description' => $page_description,
    'schema_extra' => cs_recipe_schema($recipes, cs_site_origin()),
]);
?>
			<div id="main" class="alt">
				<section id="info">
					<div class="inner">
						<header class="major">
							<h1>Vegan kitchen</h1>
						</header>
						<p>Food sits beside the practice at Creative Space: plant-based plates after class, and the odd supper with the Collective. Recipes can be added from the studio admin.</p>
					</div>
				</section>
				<section id="recipes">
					<div class="inner">
						<header class="major">
							<h2>Recipes</h2>
						</header>
						<?php if (!$recipes): ?>
							<p>No recipes yet.</p>
						<?php else: ?>
							<?php foreach ($recipes as $index => $recipe): ?>
								<?php if ($index > 0): ?><hr /><?php endif; ?>
								<article>
									<h3><?php echo cs_h($recipe['title'] ?? ''); ?></h3>
									<div class="recipe">
										<div>
											<?php $photo = cs_safe_image_src((string) ($recipe['image'] ?? '')); ?>
											<?php if ($photo !== ''): ?>
												<img src="<?php echo cs_h($photo); ?>" alt="<?php echo cs_h($recipe['title'] ?? ''); ?>" loading="lazy" decoding="async" />
											<?php endif; ?>
										</div>
										<div>
											<h4>Ingredients</h4>
											<ul>
												<?php foreach ($recipe['ingredients'] ?? [] as $item): ?>
													<li><?php echo cs_h($item); ?></li>
												<?php endforeach; ?>
											</ul>
											<?php if (!empty($recipe['icing'])): ?>
												<h5>For the icing</h5>
												<ul>
													<?php foreach ($recipe['icing'] as $item): ?>
														<li><?php echo cs_h($item); ?></li>
													<?php endforeach; ?>
												</ul>
											<?php endif; ?>
										</div>
									</div>
									<?php if (!empty($recipe['serve'])): ?>
										<h4>To serve</h4>
										<p><?php echo cs_h($recipe['serve']); ?></p>
									<?php endif; ?>
									<?php if (!empty($recipe['method'])): ?>
										<h4>Method</h4>
										<ol>
											<?php foreach ($recipe['method'] as $step): ?>
												<li><?php echo cs_h($step); ?></li>
											<?php endforeach; ?>
										</ol>
									<?php endif; ?>
								</article>
							<?php endforeach; ?>
						<?php endif; ?>
					</div>
				</section>
				<section id="chefs">
					<div class="inner">
						<header class="major">
							<h2>Featured cooks</h2>
						</header>
						<p>Dummy profiles until the live kitchen list is set. Creative Space hosts occasional suppers with friends of the studio — plant-based, unfussy, and served after the last class of the evening.</p>
						<ul>
							<li><strong>Studio kitchen</strong> — Vinay’s slow-cooker curries and pies on class nights.</li>
							<li><strong>Guest cook (placeholder)</strong> — a Hoxton baker rotating cakes through the cupboard.</li>
							<li><strong>Guest cook (placeholder)</strong> — a Collective supper built around whatever is in season at Columbia Road.</li>
						</ul>
					</div>
				</section>
			</div>
<?php
cs_layout_end();
