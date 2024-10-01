<?php

namespace WfpFundraising\Utilities;

defined( 'ABSPATH' ) || exit;

class Utils {

	public static function get_kses_array() {
		return array(
			'a'                             => array(
				'class'         => array(),
				'href'          => array(),
				'rel'           => array(),
				'title'         => array(),
				'target'        => array(),
				'style'         => array(),
				'data-link'     => array(),
				'onclick'       => array(),
				'id'            => array(),
				'data-fancybox' => array(),
			),
			'abbr'                          => array(
				'title' => array(),
			),
			'b'                             => array(),
			'blockquote'                    => array(
				'cite' => array(),
			),
			'cite'                          => array(
				'title' => array(),
			),
			'code'                          => array(),
			'pre'                           => array(),
			'del'                           => array(
				'datetime' => array(),
				'title'    => array(),
			),
			'dd'                            => array(),
			'div'                           => array(
				'class' => array(),
				'title' => array(),
				'style' => array(),
			),
			'dl'                            => array(),
			'dt'                            => array(),
			'em'                            => array(),
			'strong'                        => array(),
			'h1'                            => array(
				'class' => array(),
			),
			'h2'                            => array(
				'class' => array(),
			),
			'h3'                            => array(
				'class' => array(),
			),
			'h4'                            => array(
				'class' => array(),
			),
			'h5'                            => array(
				'class' => array(),
			),
			'h6'                            => array(
				'class' => array(),
			),
			'i'                             => array(
				'class' => array(),
			),
			'img'                           => array(
				'id'      => array(),
				'alt'     => array(),
				'class'   => array(),
				'height'  => array(),
				'src'     => array(),
				'width'   => array(),
				'style'   => array(),
				'srcset'  => array(),
				'loading' => array(),
			),
			'li'                            => array(
				'class' => array(),
			),
			'ol'                            => array(
				'class' => array(),
			),
			'p'                             => array(
				'class' => array(),
				'style' => array(),
			),
			'q'                             => array(
				'cite'  => array(),
				'title' => array(),
			),
			'span'                          => array(
				'class' => array(),
				'title' => array(),
				'style' => array(),
			),
			'iframe'                        => array(
				'src'                   => array(),
				'width'                 => array(),
				'height'                => array(),
				'scrolling'             => array(),
				'frameborder'           => array(),
				'allow'                 => array(),
				'type'                  => array(),
				'webkitallowfullscreen' => array(),
				'mozallowfullscreen'    => array(),
				'allowfullscreen'       => array(),
				'webkitallowfullscreen' => array(),
				'class'                 => array(),
				'data-origwidth'        => array(),
				'data-origheight'       => array(),
				'style'                 => array(),
			),
			'strike'                        => array(),
			'br'                            => array(),
			'table'                         => array(),
			'thead'                         => array(),
			'tbody'                         => array(),
			'tfoot'                         => array(),
			'tr'                            => array(),
			'th'                            => array(),
			'td'                            => array(),
			'colgroup'                      => array(),
			'col'                           => array(),
			'strong'                        => array(),
			'data-wow-duration'             => array(),
			'data-wow-delay'                => array(),
			'data-wallpaper-options'        => array(),
			'data-stellar-background-ratio' => array(),
			'ul'                            => array(
				'class' => array(),
			),
			'svg'                           => array(
				'class'           => true,
				'aria-hidden'     => true,
				'aria-labelledby' => true,
				'role'            => true,
				'xmlns'           => true,
				'width'           => true,
				'height'          => true,
				'viewbox'         => true, // <= Must be lower case!
			),
			'g'                             => array( 'fill' => true ),
			'title'                         => array( 'title' => true ),
			'path'                          => array(
				'd'    => true,
				'fill' => true,
			),
			'input'                         => array(
				'class' => array(),
				'type'  => array(),
				'value' => array(),
				'id'    => array(),
				'name'  => array(),
			),
			'meta'                          => array(
				'property' => array(),
				'content'  => array(),
			),
			'option'                        => array(
				'selected' => array(),
				'value'    => array(),
			),
			'select'                        => array(
				'name'     => array(),
				'class'    => array(),
				'oninput'  => array(),
				'required' => array(),
			),
			'optgroup'                      => array(
				'label' => array(),
			),
		);
	}
}
