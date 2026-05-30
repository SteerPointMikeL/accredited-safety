<?php
/**
 * Layout: staff_grid — team member cards with an optional per-member detail modal.
 *
 * A member whose "modal_content" is filled renders an interactive card (a real
 * <button>) that opens an accessible dialog; members with no detail content
 * render as static cards.
 *
 * @package ACCR_Theme
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

$eyebrow = get_sub_field( 'eyebrow' );
$title   = get_sub_field( 'title' );
$intro   = get_sub_field( 'intro' );
$cols    = get_sub_field( 'columns' ) ?: '3';
$bg      = get_sub_field( 'background' ) ?: 'default';
$members = get_sub_field( 'members' );

if ( ! $members ) {
	return;
}

$grid_class = 'staff-grid staff-grid--' . intval( $cols );

/* Stable, unique prefix for ids so multiple staff_grid sections coexist on one page. */
static $accr_staff_grid_n = 0;
$accr_staff_grid_n++;
$uid = 'staff-' . get_the_ID() . '-' . $accr_staff_grid_n;

/* Collected modal markup, printed once after the grid. */
$modals = array();

accr_section_open( array( 'background' => $bg ) );
?>
	<div class="container">
		<?php if ( $eyebrow || $title || $intro ) : ?>
			<div class="staff-grid__head">
				<?php if ( $eyebrow ) : ?><span class="eyebrow"><?php echo esc_html( $eyebrow ); ?></span><?php endif; ?>
				<?php if ( $title ) : ?><h2 class="staff-grid__title"><?php echo wp_kses_post( $title ); ?></h2><?php endif; ?>
				<?php if ( $intro ) : ?><div class="staff-grid__intro"><?php echo wp_kses_post( $intro ); ?></div><?php endif; ?>
			</div>
		<?php endif; ?>

		<div class="<?php echo esc_attr( $grid_class ); ?>">
			<?php
			$i = 0;
			foreach ( $members as $member ) :
				$i++;
				$name    = isset( $member['name'] ) ? $member['name'] : '';
				$role    = isset( $member['role'] ) ? $member['role'] : '';
				$photo   = isset( $member['photo'] ) && is_array( $member['photo'] ) ? $member['photo'] : null;
				$details = ! empty( $member['details'] ) && is_array( $member['details'] ) ? $member['details'] : array();
				$email   = isset( $member['email'] ) ? trim( (string) $member['email'] ) : '';
				$modal   = isset( $member['modal_content'] ) ? trim( (string) $member['modal_content'] ) : '';

				if ( '' === $name ) {
					continue;
				}

				$has_modal = ( '' !== $modal );
				$modal_id  = $uid . '-modal-' . $i;

				/* Build the inner card content (shared between button and static markup). */
				ob_start();
				?>
				<div class="staff-card__media">
					<?php if ( $photo && ! empty( $photo['url'] ) ) : ?>
						<img src="<?php echo esc_url( $photo['url'] ); ?>" alt="<?php echo esc_attr( $photo['alt'] ? $photo['alt'] : $name ); ?>" loading="lazy" />
					<?php else : ?>
						<span class="staff-card__noimg"><?php esc_html_e( 'Photo not available', 'accr-theme' ); ?></span>
					<?php endif; ?>
				</div>
				<div class="staff-card__body">
					<h3 class="staff-card__name"><?php echo esc_html( $name ); ?></h3>
					<?php if ( $role ) : ?><p class="staff-card__role"><?php echo esc_html( $role ); ?></p><?php endif; ?>
					<?php if ( $details ) : ?>
						<dl class="staff-card__details">
							<?php foreach ( $details as $d ) :
								$dh = isset( $d['heading'] ) ? $d['heading'] : '';
								$dt = isset( $d['text'] ) ? $d['text'] : '';
								if ( '' === $dh && '' === $dt ) {
									continue;
								}
								?>
								<?php if ( '' !== $dh ) : ?><dt><?php echo esc_html( $dh ); ?></dt><?php endif; ?>
								<?php if ( '' !== $dt ) : ?><dd><?php echo esc_html( $dt ); ?></dd><?php endif; ?>
							<?php endforeach; ?>
						</dl>
					<?php endif; ?>
					<?php if ( $has_modal ) : ?>
						<span class="staff-card__more">
							<?php esc_html_e( 'View bio', 'accr-theme' ); ?>
							<?php echo accr_icon( 'arrow_right', array( 'width' => '15', 'height' => '15', 'stroke-width' => '2.5' ) ); ?>
						</span>
					<?php endif; ?>
				</div>
				<?php
				$inner = ob_get_clean();
				?>

				<div class="staff-card<?php echo $has_modal ? ' staff-card--has-modal' : ''; ?>">
					<?php if ( $has_modal ) : ?>
						<button type="button" class="staff-card__trigger" aria-haspopup="dialog" aria-expanded="false" aria-controls="<?php echo esc_attr( $modal_id ); ?>">
							<?php echo $inner; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped — pre-escaped above. ?>
						</button>
					<?php else : ?>
						<div class="staff-card__static">
							<?php echo $inner; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped — pre-escaped above. ?>
						</div>
					<?php endif; ?>

					<?php if ( $email ) : ?>
						<a class="staff-card__email btn btn--primary btn--block" href="mailto:<?php echo esc_attr( antispambot( $email ) ); ?>">
							<?php echo esc_html( $email ); ?>
						</a>
					<?php endif; ?>
				</div>

				<?php
				if ( $has_modal ) {
					ob_start();
					?>
					<div class="staff-modal-backdrop" data-staff-modal id="<?php echo esc_attr( $modal_id ); ?>" hidden>
						<div class="staff-modal" role="dialog" aria-modal="true" aria-labelledby="<?php echo esc_attr( $modal_id ); ?>-title">
							<div class="staff-modal__head">
								<div class="staff-modal__head-inner">
									<?php if ( $photo && ! empty( $photo['url'] ) ) : ?>
										<img class="staff-modal__avatar" src="<?php echo esc_url( $photo['url'] ); ?>" alt="" />
									<?php endif; ?>
									<div>
										<h3 class="staff-modal__name" id="<?php echo esc_attr( $modal_id ); ?>-title"><?php echo esc_html( $name ); ?></h3>
										<?php if ( $role ) : ?><p class="staff-modal__role"><?php echo esc_html( $role ); ?></p><?php endif; ?>
									</div>
								</div>
								<button type="button" class="staff-modal__close" data-staff-modal-close aria-label="<?php esc_attr_e( 'Close', 'accr-theme' ); ?>">
									<?php echo accr_icon( 'close', array( 'width' => '20', 'height' => '20', 'stroke-width' => '2.5' ) ); ?>
								</button>
							</div>
							<div class="staff-modal__body">
								<?php echo wp_kses_post( wpautop( $modal ) ); ?>
								<?php if ( $details ) : ?>
									<dl class="staff-modal__details">
										<?php foreach ( $details as $d ) :
											$dh = isset( $d['heading'] ) ? $d['heading'] : '';
											$dt = isset( $d['text'] ) ? $d['text'] : '';
											if ( '' === $dh && '' === $dt ) {
												continue;
											}
											?>
											<?php if ( '' !== $dh ) : ?><dt><?php echo esc_html( $dh ); ?></dt><?php endif; ?>
											<?php if ( '' !== $dt ) : ?><dd><?php echo esc_html( $dt ); ?></dd><?php endif; ?>
										<?php endforeach; ?>
									</dl>
								<?php endif; ?>
								<?php if ( $email ) : ?>
									<a class="staff-modal__email btn btn--primary btn--block" href="mailto:<?php echo esc_attr( antispambot( $email ) ); ?>">
										<?php echo esc_html( $email ); ?>
									</a>
								<?php endif; ?>
							</div>
						</div>
					</div>
					<?php
					$modals[] = ob_get_clean();
				}
				?>
			<?php endforeach; ?>
		</div>
	</div>

	<?php
	foreach ( $modals as $m ) {
		echo $m; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped — pre-escaped above.
	}
	?>
<?php
accr_section_close();
