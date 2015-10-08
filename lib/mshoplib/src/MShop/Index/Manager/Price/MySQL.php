<?php

/**
 * @copyright Copyright (c) Metaways Infosystems GmbH, 2012
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @package MShop
 * @subpackage Catalog
 */


namespace Aimeos\MShop\Index\Manager\Price;


/**
 * MySQL based index price for searching in product tables.
 *
 * @package MShop
 * @subpackage Catalog
 */
class MySQL
	extends \Aimeos\MShop\Index\Manager\Price\Standard
	implements \Aimeos\MShop\Index\Manager\Iface
{
	private $searchConfig = array(
		'catalog.index.price.id' => array(
			'code'=>'catalog.index.price.id',
			'internalcode'=>'mindpr."priceid"',
			'internaldeps'=>array( 'LEFT JOIN "mshop_index_price" AS mindpr USE INDEX ("idx_msindpr_s_lt_cu_ty_va", "idx_msindpr_p_s_lt_cu_ty_va") ON mindpr."prodid" = mpro."id"' ),
			'label'=>'Product index price ID',
			'type'=> 'integer',
			'internaltype' => \Aimeos\MW\DB\Statement\Base::PARAM_INT,
			'public' => false,
		),
	);


	/**
	 * Returns a list of objects describing the available criterias for searching.
	 *
	 * @param boolean $withsub Return also attributes of sub-managers if true
	 * @return array List of items implementing \Aimeos\MW\Common\Criteria\Attribute\Iface
	 */
	public function getSearchAttributes( $withsub = true )
	{
		$list = parent::getSearchAttributes( $withsub );

		foreach( $this->searchConfig as $key => $fields ) {
			$list[$key] = new \Aimeos\MW\Common\Criteria\Attribute\Standard( $fields );
		}

		return $list;
	}
}