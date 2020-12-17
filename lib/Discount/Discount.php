<?php


namespace CGP\Discount;


class Discount {

	/**
	 * @var discount amount for logged in customers
	 */

	private $registeredDiscount;

	/**
	 * @var discount amount for returned in customers
	 */

	private $returnedDiscount;

	/**
	 * @var string|bool discount type
	 */

	private $discountType = false;

	public function __construct()
	{
		$this->registeredDiscount = get_option('wc_setting_cgi_registered_discount') ? intval(get_option('wc_setting_cgi_registered_discount')) : 0;
		$this->returnedDiscount = get_option('wc_setting_cgi_returned_discount') ? intval(get_option('wc_setting_cgi_returned_discount')) : 0;

		$this->filters();
		$this->actions();
	}

	public function filters()
	{
		add_filter('woocommerce_product_get_price', [ $this, 'applyDiscountProductView'], 2, PHP_INT_MAX);
	}

	public function actions()
	{
		add_action('plugins_loaded', [ $this, 'setDiscountType' ]);
		add_action('woocommerce_cart_totals_before_order_total', [ $this,  'discountMessage' ]);
		add_action('init', [ $this, 'setReturnedDiscount']);
	}

	public function setDiscountType()
	{
		if( is_user_logged_in() ) {
			if($this->registeredDiscount > 0) {
				$this->discountType = 'registered';
			}
		} elseif( isset($_COOKIE['applyDiscount'])) {
			if($this->returnedDiscount > 0) {
				$this->discountType = 'returned';
			}
		}
	}

	/**
	 * @param $price
	 * @param $product object
	 * @return float price
	 */

	public function applyDiscountProductView($price, $product)
	{
		if($this->discountType) {
			$discount = 'registered' === $this->discountType ? $this->registeredDiscount : $this->returnedDiscount;
			$price = round( $price - $price * $discount / 100, 2);
		}

		return $price;
	}

	/**
	 * Add discount message to cart totals
	 */

	public function discountMessage()
	{
		if( !$this->discountType ) {
			return;
		}

		printf(
			'<tr><th>%s</th><td>%s%% %s</td></tr>',
			__('Discount:'),
			'registered' === $this->discountType ? $this->registeredDiscount : $this->returnedDiscount,
			'registered' === $this->discountType ? __('off for logged in customers') : __('off for returned customers')
		);
	}

	/**
	 * Set returned discount
	 */

	public function setReturnedDiscount()
	{
		if( isset($_GET['applyDiscount']) && !isset($_COOKIE['applyDiscount']) ) {
			setcookie('applyDiscount', true, time() + (10 * 365 * 24 * 60 * 60), '/', parse_url(home_url(), PHP_URL_HOST));
			add_action('wp_footer', function() {
				?>
					<div class="alert alert-info" style="position: fixed; left: 0; right: 0; bottom:0; z-index: 999; padding: 40px 30px; text-align: center; display: flex; flex-wrap: wrap; justify-content: center; margin: 0;">
						<p style="font-size: 30px"><?= __('You got discount') ?> - <?= $this->returnedDiscount ?>%!</p>
					</div>
				<?php
			});
		}
	}
}