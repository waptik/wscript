<?php

class Menus {
	
	var $itemAttributes = array(
		'text' 			 => null,
		'class' 		 => null,
		'link' 			 => null,
		'show_condition' 	=> null,
		'parent'		 => null,
		'order'			 => null,
		'id'			 => null,
	);
	
	var $items = 1;
	
	function Menu( )
	{
		foreach( $this->itemAttributes as $attribute => $default_value )
		{
			$items_by = "items_by_$attribute";
			$this->$items_by = array();
		}
	}
	
	function addItem( $attributes, $id = null )
	{
		$this->items++;
		if( is_array($attributes) && !empty($attributes) )
		{
			if( is_null( $id ) )
			{
				$id = $this->items;
			}
			foreach( $this->itemAttributes as $attribute => $default_value ) 
			{
				$items_by = "items_by_$attribute";
				$_items =& $this->$items_by;
				if( isset($attributes[$attribute]) )
				{
					$_items[$id] = $attributes[ $attribute ];
				}
				else
				{
					$_items[$id] = $default_value;
				}
			}
			return TRUE;
		}
		return FALSE;
	}
	
	function updateItem( $id, $attributes ) 
	{
		if( is_array($attributes) && !empty($attributes) )
		{
			foreach( $this->itemAttributes as $attribute => $default_value ) 
			{
				$items_by = "items_by_$attribute";
				$_items =& $this->$items_by;
				if( isset($attributes[$attribute]) )
				{
					$_items[$id] = $attributes[ $attribute ];
				}
			}
			return TRUE;
		}
		return FALSE;
	}
	
	function getItem( $id )
	{
		$item = array();
		foreach( $this->itemAttributes as $attribute => $default_value )
		{
			$items_by = "items_by_$attribute";
			$_items =& $this->$items_by;
			if( isset( $_items[$id] ) )
			{
				$item[$attribute] = $_items[$id];
			}
			else
			{
				$item[$attribute] = $default_value;
			}
		}
		return $item;
	}
	
	function deleteItem( $id )
	{
		foreach( $this->itemAttributes as $attribute => $default_value )
		{
			$items_by = "items_by_$attribute";
			$_items =& $this->$items_by;
			if( isset( $_items[$id] ) )
			{
				unset($_items[$id][$attribute]);
			}
		}
		return TRUE;
	}
	
	function getItems( )
	{
		$items = array();
		foreach( $this->itemAttributes as $attribute => $default_value )
		{
			$items_by = "items_by_$attribute";
			$_items =& $this->$items_by;
			foreach( $_items as $id => $attributes )
			{
				$items[$id][$attribute] = $_items[$id];
			}
		}
		return $items;
	}
	
	function getItemsBy( $attribute, $value ) 
	{
		$items = array();
		
		$items_by = "items_by_$attribute";
		$_items =& $this->$items_by;
		
		if( !empty( $_items ) ) 
		{
			foreach( $_items as $id => $item_value ) 
			{
				if( $item_value === $value )
				{
					$items[$id][$attribute] = $item_value;
				}
			}
			
			if( !empty($items) ) 
			{
				$item_attributes = $this->itemAttributes;
				unset($item_attributes[$attribute]);
				foreach( $item_attributes as $item_attribute => $default_value )
				{
					$items_by = "items_by_$item_attribute";
					$_items =& $this->$items_by;
					foreach( $items as $id => $attributes )
					{
						if( isset( $_items[$id] ) )
						{
							$items[$id][$item_attribute] = $_items[$id];
						}
					}
				}
			}
		}
		
		return $items;
	}
	
	function getTree( $parent = 0 )
	{
		$CI = &get_instance ();
		$CI->load->library ( 'wb_array' );
		$items = $this->getItems();
		$nodes = array();
		
		foreach( $items as $id => $attributes )
		{
			if( $attributes['parent'] == $parent )
			{
				$nodes[$id] = $attributes;
				$nodes[$id]['children'] = $this->getTree( $id );
				$nodes[$id]['children'] = WB_array::sort_columns( $nodes[$id]['children'], 'order' );
			}
		}
		
		$nodes = WB_array::sort_columns( $nodes, 'order' );
		
		return $nodes;
	}
	
}
//END