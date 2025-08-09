<?php

namespace Sellkit_Pro\Contact_Segmentation\Conditions;

use Sellkit_Pro\Contact_Segmentation\Conditions\Condition_Base;

defined( 'ABSPATH' ) || die();

/**
 * Class Visitor Country.
 *
 * @package Sellkit_Pro\Contact_Segmentation\Conditions
 * @since 1.1.0
 */
class Visitor_Country extends Condition_Base {

	/**
	 * Condition name.
	 *
	 * @since 1.1.0
	 */
	public function get_name() {
		return 'visitor-country';
	}

	/**
	 * Condition title.
	 *
	 * @since 1.1.0
	 */
	public function get_title() {
		return __( 'Visitor Country', 'sellkit-pro' );
	}

	/**
	 * Condition type.
	 *
	 * @since 1.1.0
	 */
	public function get_type() {
		return self::SELLKIT_MULTISELECT_CONDITION_VALUE;
	}

	/**
	 * Gets options.
	 *
	 * @since 1.1.0
	 * @return string[]
	 */
	public function get_options() {
		$countries = [
			'afghanistan'                      => 'Afghanistan',
			'albania'                          => 'Albania',
			'algeria'                          => 'Algeria',
			'american samoa'                   => 'American Samoa',
			'andorra'                          => 'Andorra',
			'angola'                           => 'Angola',
			'anguilla'                         => 'Anguilla',
			'Antarctica'                       => 'antarctica',
			'antigua and barbuda'              => 'Antigua And Barbuda',
			'argentina'                        => 'Argentina',
			'armenia'                          => 'Armenia',
			'aruba'                            => 'Aruba',
			'australia'                        => 'Australia',
			'austria'                          => 'Austria',
			'azerbaijan'                       => 'Azerbaijan',
			'bahamas'                          => 'Bahamas',
			'bahrain'                          => 'Bahrain',
			'bangladesh'                       => 'Bangladesh',
			'barbados'                         => 'Barbados',
			'belarus'                          => 'Belarus',
			'belgium'                          => 'Belgium',
			'belize'                           => 'Belize',
			'benin'                            => 'Benin',
			'bermuda'                          => 'Bermuda',
			'bhutan'                           => 'Bhutan',
			'bolivia'                          => 'Bolivia',
			'bosnia and herzegovina'           => 'Bosnia And Herzegovina',
			'botswana'                         => 'Botswana',
			'bouvet island'                    => 'Bouvet Island',
			'brazil'                           => 'Brazil',
			'british indian ocean territory'   => 'British Indian Ocean Territory',
			'brunei'                           => 'Brunei',
			'bulgaria'                         => 'Bulgaria',
			'burkina faso'                     => 'Burkina Faso',
			'burundi'                          => 'Burundi',
			'cambodia'                         => 'Cambodia',
			'cameroon'                         => 'Cameroon',
			'canada'                           => 'Canada',
			'cape verde'                       => 'Cape Verde',
			'cayman islands'                   => 'Cayman Islands',
			'central african republic'         => 'Central African Republic',
			'chad'                             => 'Chad',
			'cocos (keeling) islands'          => 'Cocos (Keeling) Islands',
			'chile'                            => 'Chile',
			'china'                            => 'China',
			'colombia'                         => 'Colombia',
			'comoros'                          => 'Comoros',
			'congo'                            => 'Congo',
			'cook islands'                     => 'Cook Islands',
			'costa rica'                       => 'Costa Rica',
			'cote d\'ivoire'                   => 'Cote D\'ivoire',
			'croatia'                          => 'Croatia',
			'cuba'                             => 'Cuba',
			'cyprus'                           => 'Cyprus',
			'czech republic'                   => 'Czech Republic',
			'democratic republic of the congo' => 'Democratic Republic of the Congo',
			'denmark'                          => 'Denmark',
			'djibouti'                         => 'Djibouti',
			'dominica'                         => 'Dominica',
			'dominican republic'               => 'Dominican Republic',
			'ecuador'                          => 'Ecuador',
			'egypt'                            => 'Egypt',
			'el salvador'                      => 'El Salvador',
			'equatorial guinea'                => 'Equatorial Guinea',
			'eritrea'                          => 'Eritrea',
			'estonia'                          => 'Estonia',
			'eswatini'                         => 'Eswatini',
			'ethiopia'                         => 'Ethiopia',
			'falkland islands'                 => 'Falkland Islands',
			'faroe islands'                    => 'Faroe Islands',
			'federated states of micronesia'   => 'Federated States Of Micronesia',
			'fiji'                             => 'Fiji',
			'finland'                          => 'Finland',
			'france'                           => 'France',
			'french guiana'                    => 'French Guiana',
			'french polynesia'                 => 'French Polynesia',
			'french southern territories'      => 'French Southern Territories',
			'gabon'                            => 'Gabon',
			'gambia'                           => 'Gambia',
			'georgia'                          => 'Georgia',
			'germany'                          => 'Germany',
			'ghana'                            => 'Ghana',
			'gibraltar'                        => 'Gibraltar',
			'greece'                           => 'Greece',
			'greenland'                        => 'Greenland',
			'grenada'                          => 'Grenada',
			'guadeloupe'                       => 'Guadeloupe',
			'guam'                             => 'Guam',
			'guatemala'                        => 'Guatemala',
			'guernsey'                         => 'Guernsey',
			'guinea'                           => 'Guinea',
			'guinea bissau'                    => 'Guinea Bissau',
			'guyana'                           => 'Guyana',
			'haiti'                            => 'Haiti',
			'honduras'                         => 'Honduras',
			'hong kong'                        => 'Hong Kong',
			'hungary'                          => 'Hungary',
			'iceland'                          => 'Iceland',
			'india'                            => 'India',
			'indonesia'                        => 'Indonesia',
			'iran'                             => 'Iran',
			'iraq'                             => 'Iraq',
			'ireland'                          => 'Ireland',
			'isle of man'                      => 'Isle of Man',
			'israel'                           => 'Israel',
			'italy'                            => 'Italy',
			'jamaica'                          => 'Jamaica',
			'japan'                            => 'Japan',
			'jersey'                           => 'Jersey',
			'jordan'                           => 'Jordan',
			'kazakhstan'                       => 'Kazakhstan',
			'kenya'                            => 'Kenya',
			'kiribati'                         => 'Kiribati',
			'kosovo'                           => 'Kosovo',
			'kuwait'                           => 'Kuwait',
			'kyrgyzstan'                       => 'Kyrgyzstan',
			'laos'                             => 'Laos',
			'latvia'                           => 'Latvia',
			'lebanon'                          => 'Lebanon',
			'lesotho'                          => 'Lesotho',
			'liberia'                          => 'Liberia',
			'libyan arab jamahiriya'           => 'Libyan Arab Jamahiriya',
			'liechtenstein'                    => 'Liechtenstein',
			'lithuania'                        => 'Lithuania',
			'luxembourg'                       => 'Luxembourg',
			'macao'                            => 'Macao',
			'macedonia'                        => 'Macedonia',
			'madagascar'                       => 'Madagascar',
			'malawi'                           => 'Malawi',
			'malaysia'                         => 'Malaysia',
			'maldives'                         => 'Maldives',
			'mali'                             => 'Mali',
			'malta'                            => 'Malta',
			'marshall islands'                 => 'Marshall Islands',
			'martinique'                       => 'Martinique',
			'mauritania'                       => 'Mauritania',
			'mauritius'                        => 'Mauritius',
			'mexico'                           => 'Mexico',
			'monaco'                           => 'Monaco',
			'mongolia'                         => 'Mongolia',
			'montenegro'                       => 'Montenegro',
			'montserrat'                       => 'Montserrat',
			'morocco'                          => 'Morocco',
			'mozambique'                       => 'Mozambique',
			'myanmar'                          => 'Myanmar',
			'namibia'                          => 'Namibia',
			'nauru'                            => 'Nauru',
			'nepal'                            => 'Nepal',
			'netherlands'                      => 'Netherlands',
			'netherlands antilles'             => 'Netherlands Antilles',
			'new caledonia'                    => 'New Caledonia',
			'new zealand'                      => 'New Zealand',
			'nicaragua'                        => 'Nicaragua',
			'niger'                            => 'Niger',
			'nigeria'                          => 'Nigeria',
			'norfolk island'                   => 'Norfolk Island',
			'northern mariana islands'         => 'Northern Mariana Islands',
			'north korea'                      => 'North Korea',
			'north macedonia'                  => 'North Macedonia',
			'norway'                           => 'Norway',
			'niue'                             => 'Niue',
			'oman'                             => 'Oman',
			'pakistan'                         => 'Pakistan',
			'palau'                            => 'Palau',
			'palestine'                        => 'Palestine',
			'panama'                           => 'Panama',
			'papua new guinea'                 => 'Papua New Guinea',
			'paraguay'                         => 'Paraguay',
			'peru'                             => 'Peru',
			'philippines'                      => 'Philippines',
			'pitcairn islands'                 => 'Pitcairn Islands',
			'poland'                           => 'Poland',
			'portugal'                         => 'Portugal',
			'puerto rico'                      => 'Puerto Rico',
			'qatar'                            => 'Qatar',
			'republic of moldova'              => 'Republic Of Moldova',
			'reunion'                          => 'Reunion',
			'romania'                          => 'Romania',
			'russia'                           => 'Russia',
			'rwanda'                           => 'Rwanda',
			'saint helena'                     => 'Saint Helena',
			'saint kitts and nevis'            => 'Saint Kitts And Nevis',
			'saint lucia'                      => 'Saint Lucia',
			'saint martin'                     => 'Saint Martin',
			'saint pierre and miquelon'        => 'Saint Pierre and Miquelon',
			'saint vincent and the grenadines' => 'Saint Vincent And The Grenadines',
			'samoa'                            => 'Samoa',
			'san marino'                       => 'San Marino',
			'sao tome and principe'            => 'Sao Tome And Principe',
			'saudi arabia'                     => 'Saudi Arabia',
			'senegal'                          => 'Senegal',
			'serbia'                           => 'Serbia',
			'seychelles'                       => 'Seychelles',
			'singapore'                        => 'Singapore',
			'sint maarten'                     => 'Sint Maarten',
			'slovakia'                         => 'Slovakia',
			'slovenia'                         => 'Slovenia',
			'solomon islands'                  => 'Solomon Islands',
			'Somalia'                          => 'Somalia',
			'south africa'                     => 'South Africa',
			'south korea'                      => 'South Korea',
			'south sudan'                      => 'South Sudan',
			'spain'                            => 'Spain',
			'sri lanka'                        => 'Sri Lanka',
			'st kitts and nevis'               => 'St Kitts and Nevis',
			'sudan'                            => 'Sudan',
			'suriname'                         => 'Suriname',
			'svalbard and jan mayen'           => 'Svalbard and Jan Mayen',
			'swaziland'                        => 'Swaziland',
			'sweden'                           => 'Sweden',
			'switzerland'                      => 'Switzerland',
			'syrian arab republic'             => 'Syrian Arab Republic',
			'taiwan'                           => 'Taiwan',
			'tajikistan'                       => 'Tajikistan',
			'tanzania'                         => 'Tanzania',
			'thailand'                         => 'Thailand',
			'togo'                             => 'Togo',
			'tonga'                            => 'Tonga',
			'trinidad and tobago'              => 'Trinidad And Tobago',
			'tunisia'                          => 'Tunisia',
			'turkey'                           => 'Turkey',
			'turks and caicos islands'         => 'Turks and Caicos Islands',
			'turkmenistan'                     => 'Turkmenistan',
			'uganda'                           => 'Uganda',
			'ukraine'                          => 'Ukraine',
			'united arab emirates'             => 'United Arab Emirates',
			'united kingdom of great britain and northern ireland' => 'United Kingdom',
			'united states of america'         => 'United States',
			'uruguay'                          => 'Uruguay',
			'uzbekistan'                       => 'Uzbekistan',
			'vanuatu'                          => 'Vanuatu',
			'venezuela'                        => 'Venezuela',
			'vietnam'                          => 'Vietnam',
			'virgin islands british'           => 'Virgin Islands British',
			'virgin islands u.s.'              => 'Virgin Islands U.S.',
			'western sahara'                   => 'Western Sahara',
			'yemen'                            => 'Yemen',
			'zambia'                           => 'Zambia',
			'zimbabwe'                         => 'Zimbabwe',
		];

		$input_value = sellkit_htmlspecialchars( INPUT_GET, 'input_value' );

		return sellkit_filter_array( $countries, $input_value );
	}

	/**
	 * It is pro feature or not.
	 *
	 * @since 1.1.0
	 */
	public function is_pro() {
		return true;
	}

	/**
	 * It searchable.
	 *
	 * @since 1.1.0
	 */
	public function is_searchable() {
		return true;
	}

	/**
	 * If it's valid or not.
	 *
	 * @since 1.8.0
	 * @param array  $condition_value Condition value.
	 * @param string $operator_name Operator name.
	 * @return bool
	 * @SuppressWarnings(PHPMD.NPathComplexity)
	 * @SuppressWarnings(PHPMD.CyclomaticComplexity)
	 */
	public function is_valid( $condition_value, $operator_name ) {
		if ( empty( $condition_value ) ) {
			return false;
		}

		$updated_country_names = [
			'democratic republic of the congo' => 'dr congo',
			'congo' => 'congo republic',
			'cote d\'ivoire' => 'ivory coast',
			'cape verde' => 'cabo verde',
			'czech republic' => 'czechia',
			'federated states of micronesia' => 'micronesia',
			'united kingdom of great britain and northern ireland' => 'united kingdom',
			'gambia' => 'the gambia',
			'guinea bissau' => 'guinea-bissau',
			'libyan arab jamahiriya' => 'libya',
			'republic of moldova' => 'moldova',
			'netherlands' => 'the netherlands',
			'syrian arab republic' => 'syria',
			'turkey' => 'türkiye',
			'united states of america' => 'united states',
		];

		$user_country = '';

		if ( ! is_user_logged_in() && isset( $_COOKIE['sellkit_contact_segmentation'] ) ) {
			$cookie_data  = sanitize_text_field( wp_unslash( $_COOKIE['sellkit_contact_segmentation'] ) );
			$decoded_data = json_decode( $cookie_data );

			if ( is_object( $decoded_data ) && isset( $decoded_data->visitor_country ) ) {
				$user_country = sanitize_text_field( $decoded_data->visitor_country );
			}
		}

		if ( is_user_logged_in() ) {
			$current_user = wp_get_current_user();

			$contact_segmentagion = sellkit()->db->get( 'contact_segmentation', [
				'email' => $current_user->user_email,
			] );

			if ( isset( $contact_segmentagion[0] ) ) {
				$user_country = $contact_segmentagion[0]['visitor_country'];
			}
		}

		if ( 'is-any-of' === $operator_name ) {
			foreach ( $condition_value as $country_condition ) {
				if ( array_key_exists( $country_condition, $updated_country_names ) ) {
					$country_condition = $updated_country_names[ $country_condition ];
				}

				if ( isset( $country_condition ) && ! empty( $user_country ) && $country_condition === $user_country ) {
					return true;
				}
			}

			return false;
		}

		if ( 'is-none-of' === $operator_name ) {
			foreach ( $condition_value as $country_condition ) {
				if ( array_key_exists( $country_condition, $updated_country_names ) ) {
					$country_condition = $updated_country_names[ $country_condition ];
				}

				if ( isset( $country_condition ) && ! empty( $user_country ) && $country_condition === $user_country ) {
					return false;
				}
			}

			return true;
		}
	}
}
