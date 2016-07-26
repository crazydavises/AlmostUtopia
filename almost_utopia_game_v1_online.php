<?php
/*
Plugin Name: Almost Utopia DataTracking
Plugin URI: http://almost-utopia.com
Description: Almost Utopia Game
Version: 0.1
Author: Amy Davis
Author URI: http://growingliketrees.blogspot.com
*/


global $finanzasfilios_db_version;
$infanzasfilios_db_version = 1.0;

class Finanzes
{		
	var $currentDay;
	var $dbInit = false;
		
	function __construct()
	{
		add_action( 'init', array( $this, 'InitDB' ) ); 		
		add_action( 'wp_head', array($this, 'addHeaderCode') );
		add_shortcode( 'GameHome', array( $this, 'GameHome' ) );
		add_shortcode( 'LoadCountry', array( $this, 'LoadCountryPage' ) );
		add_shortcode( 'Trader', array( $this, 'LoadTraderData' ) );
		add_shortcode( 'Bank', array( $this, 'LoadBankData' ) );
		add_shortcode( 'CentralBank', array( $this, 'LoadCentralBank') );
		add_shortcode( 'Consumption', array( $this, 'LoadConsumptionData') );
		add_shortcode( 'Population', array( $this, 'LoadPopulationData') );
		add_shortcode( 'Taxes', array( $this, 'LoadTaxData') );
		add_shortcode( 'GovernmentPrograms', array( $this, 'LoadGovtProgramData') );
		add_shortcode( 'Diplomat', array($this, 'LoadDiplomat') );

	}
	
	function InitDB()
	{
		$dbInit = true;
		$output = '';
		$this->FinanzesDB = new wpdb('heibec', 'truelovewaits', 'almost_utopia_game', 'mysql.almost-utopia.com');
	    $this->FinanzesDB->show_errors();
	
		$this->currentDay = 0;
		$this->viewDay = 0;
		
		$dayStats = $this->FinanzesDB->get_row( "SELECT current_day, viewing_day FROM game_state");
		
		$this->currentDay = $dayStats->current_day;
		$this->viewDay = $dayStats->viewing_day;
			
		$output .= 'now currentDay = ' . $this->currentDay . '<br />';
		//$this->currentDay++;
		
		$this->army_properties = $this->FinanzesDB->get_results("SELECT description, name, plural, field_name, unit, cost_per_unit, resell_per_unit, value_per_unit, loss_unit, loss_print, trader_table, value_pretty_print FROM army_properties");
		$this->industry_properties = $this->FinanzesDB->get_results("SELECT description, name, plural, field_name, unit, cost_per_unit, resell_per_unit, trader_table, value_per_unit, value_pretty_print FROM industry_properties");
		$this->bank_properties = $this->FinanzesDB->get_results("SELECT Name, pay_field_name, received_field_name, readonly FROM bank_properties");
		$this->entities = $this->FinanzesDB->get_results("SELECT Name, bank_table, external_bank_field, internal_paid, internal_received, aggregate, link FROM entities");
		$this->consumables = $this->FinanzesDB->get_results("SELECT Name, field_name, production_field, units_pretty_print, formula FROM consumption_properties ORDER BY id");
		$this->diplomat_properties = $this->FinanzesDB->get_results("SELECT name, field_name, units_pretty_print FROM diplomat_properties");
		$this->taxes = $this->FinanzesDB->get_results("SELECT Name, field_name, money_recd_field_name FROM tax_types");
		$this->population = $this->FinanzesDB->get_results("SELECT Name, field_name, readonly FROM population_properties");
		$this->scenarios = $this->FinanzesDB->get_results("SELECT id, Title, scene, Notes FROM scenarios WHERE day=" . $this->viewDay );
		$this->weathers = $this->FinanzesDB->get_results("SELECT id, description FROM available_weather");
		$this->arms_trader_properties = $this->FinanzesDB->get_results("SELECT description, name, plural, field_name, unit, value_per_unit, value_pretty_print FROM arms_trader_properties");
		$this->real_estate_properties = $this->FinanzesDB->get_results("SELECT description, name, plural, field_name, unit, value_per_unit, value_pretty_print FROM real_estate_properties");
		add_filter('query_vars', 'parameter_queryvars' );  
		
		return $output;
	}
	function addheaderCode()
	{
        echo '<link type="text/css" rel="stylesheet" href="' . get_bloginfo('wpurl') . '/wp-content/plugins/Finanzesfilios/utopia_style.css" />' . "\n";
	}	
	
	
	
	
	function updateValue( $table, $day, $field, $new_quant, $relative)
	{
		$val = $new_quant;		
		$cur_data = $this->FinanzesDB->get_row("SELECT day, " . $field . " from " . $table . " WHERE day=". $day );
		$output = '';
		
		if ($cur_data)
		{
			if( $relative )
			{
				$val = $cur_data->$field + $new_quant;
				$output .= ' adding ' . $new_quant . ' new val is ' . $val . '<br />';
			}

			if($cur_data->$field != $val)
			{
					$this->FinanzesDB->update($table, array( $field => $val),
												array('day' => $day),
												array('%d'),
												array('%d'));
			}
		}
		else
		{
				$this->FinanzesDB->insert($table, 
								array('day' => $day, 
											$field => $val),
								array ('%d', '%d'));
								
		}
		return $output;
	}
	
	function getSelectionFields( $country, $category, $includeTotals)
	{
		$selection_fields = '';
		
			// make the list of fields to look for
//		if(!$this->isCountry($country))
//			$category = $country;
	
		$property_name = $category . '_properties';
	
		foreach ($this->$property_name as $prop)
		{
			if (!$this->fieldInCountry( $prop->field_name, $country))
				$selection_fields = $selection_fields; // no change 
			else
				$selection_fields .= $prop->field_name . ', ';
		}
		

		if ($includeTotals)
		{
//			if( $this->isCountry($country) && $category == 'industry')
//				$selection_fields .= 'total_production';
//			else
				$selection_fields .= 'balance';
		}
		else
			$selection_fields = substr($selection_fields, 0, -2);
			
		return $selection_fields;
	
		
	}
	
	function fieldInCountry( $field_name, $country)
	{
		$result = true;
		if( ($field_name == 'num_bases' && $country == 'arms_trader') ||
			($field_name == 'total_developmentcosts' && $country == 'real_estate') ||
			($field_name == 'num_soldiers' && $country == 'arms_trader' )) 
				$result = false;
			return $result;
		
	}
	
	function carryForwardField( $field_name, $country)
	{
		$result = true;
		
	}
	
	
	function LoadDiplomat( $atts)
	{
		extract (shortcode_atts( array( 'country'=>'Filios'), $atts) );
		
		$output = '';
				
		$output = $this->LoadCountryHeader( $country );
		
		$output .= '<strong>' . $country . ' Diplomat Day ' . $this->viewDay . ' </strong><br /><br />';
		if ($this->viewDay < $this->currentDay)
			$output .= '(day has ended)<br />';

		$country = strtolower($country);
		
		$tableName = $country . '_diplomat';	
		if( isset($_POST['add']) )
		{
			$balID = $this->FinanzesDB->get_var("SELECT MAX(id), balance FROM $tableName");
			$oldBal = $this->FinanzesDB->get_var("SELECT balance FROM $tableName WHERE id=$balID");
			$output .= 'oldBal = ' . $oldBal . '<br />';
			$moneyMade = $_POST['price'];
			if($_POST['action'] == 'Buy')
				$moneyMade *= -1;
				
			$newBal = $oldBal + $moneyMade;

			$this->FinanzesDB->insert($tableName, 
								array('day' => $this->currentDay, 
									  'action' => $_POST['action'],
									  'item_name' =>$_POST['commodity'], 
									  'quantity' => $_POST['quantity'],
									  'other_party' =>$_POST['other_party'],
									  'price'=>$_POST['price'],
									  'balance'=>$newBal),						  
								array ('%d', '%s', '%s', '%s','%s', '%s' ));
			$other_party = strtolower($_POST['other_party']);
			$bankTable = $country . '_bank';
			$otherBank = $other_party . '_bank';
			if( $_POST['action'] == 'Buy')
			{
				$meField = $other_party . '_paid';
				$otherField = $country . '_received';
			}
			else
			{
				$meField = $other_party . '_received';
				$otherField = $country . '_paid';
			}
			$this->updateValue( $bankTable, $this->currentDay, $meField, $_POST['price'], true);

			//$this->updateValue( $otherBank, $this->currentDay, $otherField, $_POST['price'], true);
			
		}
		
	//	$selection_fields = $this->getSelectionFields($country, 'diplomat', true);		
 		$theData = $this->FinanzesDB->get_results("SELECT action, quantity, item_name, other_party, price, balance FROM " . $tableName . " WHERE day = " . $this->viewDay . " ORDER by id" );
		if($theData)
		{
			$output .= '<strong>Transactions</strong><br />';	
			$output .= '<table>';
			$output .= '<tr><th>Action</th><th>Quantity</th><th>Item</th><th>From/To</th><th>Sale Price</th><th>Running Balance</th></tr>';
			foreach( $theData as $entry)
			{	
				$output .= '<tr><td>' . $entry->action . '</td><td> ' .$entry->quantity . '</td><td> ' .$entry->item_name . '</td><td> ' .$entry->other_party . '</td><td>' . $entry->price . '</td><td>' . $entry->balance . '</td></tr>';				
			}
			$output .= '</table><br />';
		}
		if($this->viewDay == $this->currentDay)
		{
				
			$output .= '<strong>Add Transaction</strong>';
			$output .= '<form method="post" action="">';
			$output .= '<table>';
			$output .= "<td>Action<select name='action'><option value='Buy'>Buy</option><option value='Sell'>Sell</option></select></td>";
			$output .= "<td>Amount<input type='text' name='quantity' size='5'></td>";
			$output .= "<td>Pounds/Gallons/Barrels/Pounds of<select name='commodity'>";
			$commodities = array('Food', 'Water', 'Energy', 'Construction Materials');
			foreach($commodities as $c)
				$output .= "<option value='$c'>$c</option>";
			$output .= "</select></td>";
			
			
			$output .= "<td> To/From <select name='other_party'>";
			$countries = array('Filios', 'Intelibus', 'Xtensica');
			foreach($countries as $c)
			{
				if( strtolower($c) != $country)
					$output .= "<option value='$c'>$c</option>";
			}
			$output .= "</select></td>";
		
			$output .= '<td> For Total Price: <input type="text" name="price" size="15" length="30" /> </td>';
			$output .= '</table>';
			$output .= '<input type="submit" name="add" value="Add Transaction" />';
			$output .= '</form>';
		
		}
	$output .= $this->LoadFooter();
	return $output;

	}
	
	function LoadTraderData( $atts)
	{
		extract( shortcode_atts( array(
				'country' => 'Filios',
				'category' => 'Army'), $atts ) );
		$output = '';
		if ($country != 'Arms' && $country != 'Real Estate')
			$output = $this->LoadCountryHeader( $country );
		else
			$output = $this->LoadCountryHeader(null);
			
		$output .= '<strong>' . $country . ' ' . $category . ' Day ' . $this->viewDay . ' </strong><br /><br />';
		if ($this->viewDay < $this->currentDay)
			$output .= '(day has ended)<br />';

		$country = strtolower($country);
		$category = strtolower($category);
				
		$tableName = $country . '_' . $category . '_log';
				
		$output .= '<form method="post" action="">';
		$output .= '<table>';
		
		if ($country == 'arms')
		{
			$tableName = 'arms_trader';
			$category = 'arms_trader';
		}
		if ($country == 'real estate')
		{
			$tableName = 'real_estate_developer';
			$category = 'real_estate';
		}
		
		$property_name = $category . '_properties';
		$selection_fields = $this->getSelectionFields($country, $category, true);
		//$output .= 'selection_fields = ' . $selection_fields . '<br />';
		if ( isset($_POST['change_numbers']))
		{
			$theData = $this->FinanzesDB->get_row("SELECT " . $selection_fields . " FROM " . $tableName . " WHERE day = " . $this->viewDay );
			// explode doesn't work?  I don't know why
			$first_word_ends = strpos($_POST['change_numbers'], " ");
				
			$slug = substr($_POST['change_numbers'], 0, $first_word_ends);
			$rest = substr($_POST['change_numbers'], $first_word_ends+1);
			$second_word_ends = strpos($rest, " ");
			
			if ($second_word_ends === false)
				$second_word_ends == 2;
					
			//$output .= 'slug = '. $slug . ' rest = ' . $rest . ' second word ends = ' . $second_word_ends . ' <br />';

			$operation = "unknown";
			switch($slug)
			{
				case "Sell":
					$operation = "sell";
					$item = substr($rest, $second_word_ends+1);
					break;
				case "Buy":
					$operation = "buy";
					$item = substr($rest, $second_word_ends+1);
					break;
				default:
					$operation = "lose";
					$item = substr( $rest, 0, $second_word_ends );
					break;
			}
			
			$item_field = '';
			$old_num = 0;
			foreach($this->$property_name as $prop)
			{
				if (strpos($prop->name, $item) !== false )
				{
					$item_field = $prop->field_name;
					break;
				}
			}
			
			//$output .= ' operation = ' . $operation . ' field = ' . $item_field . ' item = ' . $item . '<br />';
			$old_num = $theData->$item_field;
			$new_num = $old_num;	
			$balance_field = 'balance';

			if( $operation == "sell" || $operation == "lose" || $operation == "use")
			{
				if( $operation == "lose" && $category == 'army')
					$new_num -= $prop->loss_unit;
				else
					$new_num -= $prop->unit;
					
				$diff = $new_num - $old_num;	
				$cost = $diff * $prop->value_per_unit;		
				//$output .= 'diff = ' . $diff . ' cost = ' . $cost . '<br />';
				
				$this->updateValue( $tableName, $this->currentDay, $item_field, $new_num, false );
				if( $operation == "sell")
				{

					$this->updateValue( $tableName, $this->currentDay, $balance_field, $cost, true );
					$bankTable = $country . '_bank';
					$bankField = $category . '_received';
				    $this->updateValue( $bankTable, $this->currentDay, $bankField, $prop->resell_per_unit, true);
					
					if (isset( $prop->trader_table) && $prop->trader_table != '0')
					{
						$traded = $diff * -1;
						$this->updateValue( $prop->trader_table, $this->currentDay, $item_field, $diff, true);
						$increased_value = $traded * $prop->resell_per_unit; // should be negative.  trader paid for this
						$this->updateValue( $prop->trader_table, $this->currentDay, 'balance', $increased_value, true);
					}	
				}		
			}
			else if ($operation == "buy")
			{
				$new_num += $prop->unit;
				$diff = $new_num - $old_num;			
				$cost = $diff * $prop->value_per_unit;		
				//$output .= 'diff = ' . $diff . ' cost = ' . $cost . '<br />';
				//$output .= 'tableName=' . $tableName . '<br />';
				$this->updateValue( $tableName, $this->currentDay, $item_field, $new_num, false );
				$this->updateValue( $tableName, $this->currentDay, $balance_field, $cost, true );
				
				$bankTable = $country . '_bank';
				$bankField = $category . '_paid';
				$this->updateValue( $bankTable, $this->currentDay, $bankField, $prop->cost_per_unit, true);
				
				if (isset( $prop->trader_table) && $prop->trader_table != '0')
				{
					$traded = $diff * -1;
					$this->updateValue( $prop->trader_table, $this->currentDay, $item_field, $traded, true);
					$increased_value = $diff * $prop->cost_per_unit; // trader made money off of this
					$this->updateValue( $prop->trader_table, $this->currentDay, 'balance', $increased_value, true);
				}
			}
			
		}
	/*	if (isset($_POST['update_money']))
		{
			
			$new_paid = $_POST["money_paid"];
			$old_paid = $_POST["money_paid_old"];
			if($new_paid != $old_paid)
				$this->updateValue( $tableName, $this->currentDay, 'money_paid', $new_paid, false);
			
			$new_received = $_POST["money_received"];
			$old_received = $_POST["money_received_old"];
			if($new_received != $old_received)
				$this->updateValue( $tableName, $this->currentDay, 'money_received', $new_received, false);
			
		}  */
		
  		$theData = $this->FinanzesDB->get_row("SELECT " . $selection_fields . " FROM " . $tableName . " WHERE day = " . $this->viewDay );

		if($theData)
		{	
			foreach( $this->$property_name as $prop)
			{	
				$skip = false;
//				if ( !$this->fieldInCountry($prop->field_name, $country))
//					$skip = true;
				
					
				if ($skip == false)
				{
					$field = $prop->field_name;
					if( isset( $theData->$field))
					{
						$num_items = $theData->$field;
						$total_item_price = $num_items * $prop->value_per_unit;	
						$unit_item_string = $prop->unit . ' ' . $prop->name;
						$lost_item_string = $unit_item_string . ' Lost';
						if( isset( $prop->loss_unit))
							$lost_item_string = $prop->loss_unit . ' ' . $prop->name . ' ' . $prop->loss_print;  
						$output .= '<tr><td><strong>' . $prop->description .' ' . $num_items . ' ' .  $prop->plural . '</strong>,';
						$output .= $prop->value_pretty_print . 'Total: $' . $total_item_price . '</td>';
			
						if($this->viewDay == $this->currentDay && $this->isCountry($country))
						{
							$output .= '<td><input type="submit" name="change_numbers" value="Buy ' . $unit_item_string . '">';
			
							if( $num_items > 0)
							{
								$output .= '<input type="submit" name="change_numbers" value="Sell ' . $unit_item_string . '">';
								if( $country != 'arms' && $country != 'real estate')
									$output .= '<input type="submit" name="change_numbers" value="' . $lost_item_string . '">';
							}
						}
						$output .= '</td></tr>';
					}
				}
			}

			// add the ending fields for arms trader and real estate developer.
	/*		if( $country == 'arms' || $country == 'real estate')
			{
				$output .= '<tr><td> Amount Paid <input name="money_paid" type="text" readonly="readonly" size="30" value="' . $theData->money_paid . '"';
				$output .='></td></tr><tr><td> Amount Received <input name="money_received" type="text" readonly="readonly" size="30" value="' . $theData->money_received . '"></td></tr>';
				$output .= '</table>';
				
			}
			else */
			{
				$amt = 	$theData->balance;
				$output .= '<tr><td> Balance ';
			/*	if($this->isCountry($country))
				{
					if( $amt < 0 )
					{
				
						$output .= '(Gain) ';
						$amt *= -1;
					}
					else
						$output .= '(Costs)' ; 
				}*/

				$output .= '<input name="abs_costs" type="text" readonly="readonly" size="30" value="' . $amt . '"></td></tr>';
				$output .= '</table>';

			}
			
		}

		$output .= '</form>';
		$output .= $this->LoadFooter();		
		return $output;
	}
	
	
	function LoadJobData( $atts)
	{
		extract( shortcode_atts( array(
				'country' => 'Filios',
				'category' => 'Army'), $atts ) );
	
		$output = $this->LoadCountryHeader( $country );
		
		$output .= '<strong>' . $country . ' ' . $category . ' Day ' . $this->viewDay . ' </strong><br /><br />';
		if ($this->viewDay < $this->currentDay)
			$output .= '(day has ended)<br />';

		$country = strtolower($country);
		$category = strtolower($category);
		
		$tableName = $country . '_' . $category . '_log';
		$output .= '<form method="post" action="">';
		$output .= '<table>';
		
		$selection_fields = '';
		$property_name = $category . '_properties';
		// make the list of fields to look for
		foreach ($this->$property_name as $prop)
		{
			$selection_fields .= $prop->field_name . ', ';
		}
		$selection_fields = substr($selection_fields, 0, -2);
		if ($_POST['change_numbers'])
		{
			$armyData = $this->FinanzesDB->get_row("SELECT " . $selection_fields . " FROM " . $tableName . " WHERE day = " . $this->viewDay );
			// explode doesn't work?  I don't know why
			$first_word_ends = strpos($_POST['change_numbers'], " ");
				
			$slug = substr($_POST['change_numbers'], 0, $first_word_ends);
			$rest = substr($_POST['change_numbers'], $first_word_ends+1);
			
			$second_word_ends = strpos($rest, " ");
			
			if ($second_word_ends == false)
				$second_word_ends == 2;
					
			
			$operation = "unknown";
			switch($slug)
			{
				case "Sell":
					$operation = "sell";
					$item = substr($rest, $second_word_ends+1);
					break;
				case "Buy":
					$operation = "buy";
					$item = substr($rest, $second_word_ends+1);
					break;
				default:
					$operation = "lose";
					$item = substr($rest, 0, $second_word_ends);
					break;
			}
			
			$item_field = '';
			$old_num = 0;
			foreach($this->$property_name as $prop)
			{
				if ($prop->pretty_name == $item)
				{
					$item_field = $prop->field_name;
					break;
				}
			}
			
			$old_num = $armyData->$item_field;
			$new_num = $old_num;
			
			if( $operation == "sell" || $operation == "lose")
			{
				$new_num -= $prop->unit;
				$this->updateValue( $tableName, $this->currentDay, $item_field, $new_num, false );
				// a bank operation?
			}
			else if ($operation == "buy")
			{
				$new_num += $prop->unit;
				$this->updateValue( $tableName, $this->currentDay, $item_field, $new_num, false );
				// a bank operation
			}
			
		}
		
	
		$armyData = $this->FinanzesDB->get_row("SELECT " . $selection_fields . " FROM " . $tableName . " WHERE day = " . $this->currentDay );


		if($armyData)
		{	
			foreach( $this->$property_name as $prop)
			{	
				$field = $prop->field_name;
				$num_items = $armyData->$field;
				$total_item_price = $num_items * $prop->value_per_unit;	
				$unit_item_string = $prop->unit . ' ' . $prop->pretty_name;
				
				$output .= '<tr><td><strong>' . $prop->description .' ' . $num_items . ' ' .  $prop->name . 's</strong>,';
				$output .= $prop->value_pretty_print . 'Total: $' . $total_item_price . '</td>';
			
				if($this->viewDay == $this->currentDay)
				{
					$output .= '<td><input type="submit" name="change_numbers" value="Buy ' . $unit_item_string . '">';
		
					if( $num_items > 0)
					{
						$output .= '<input type="submit" name="change_numbers" value="Sell ' . $unit_item_string . '">';
						$output .= '<input type="submit" name="change_numbers" value="' . $unit_item_string . ' Lost">';
					}
				}
				$output .= '</td></tr>';
			}
		}
	
		
		$output .= '</table>';
		$output .= '</form>';
		$output .= $this->LoadFooter();
		return $output;
	}
	

	function LoadPopulationData( $atts )
	{
		extract( shortcode_atts( array(
				'country' => 'Central'), $atts ) );
		
		
		if (isset($_POST['update']))
		{
			$country = $_POST['country'];
			$tableName = $country . '_population_log';
			
			foreach($this->population as $prop)
			{
				if( !$prop->readonly)
				{
				$new_amt = $_POST[$prop->field_name];
				$this->updateValue( $tableName, $this->currentDay, $prop->field_name, $new_amt, false );
				}
			}
		}
		
		
		$output = $this->LoadCountryHeader( $country );
		
		$output .= '<strong>' . $country . ' Population Day ' . $this->viewDay . ' </strong><br /><br />';
	
		$country = strtolower($country);
		$tableName = $country . '_population_log';
		
		$selection_fields = '';
		
		// make the list of fields to look for
		foreach ($this->population as $prop)
		{
			$selection_fields .= $prop->field_name . ', ';
		}
		
		$selection_fields = substr($selection_fields, 0, -2);
		
		$data = $this->FinanzesDB->get_row("SELECT " . $selection_fields . " FROM " . $tableName . " WHERE day = " . $this->viewDay  );
		$readonly = false;
		if( $this->currentDay != $this->viewDay)
			$readonly = true;
		if($data)
		{	
			$output .= '<form method="post" action="">';
			$output .= '<input type="hidden" name="country" value="' . $country . '"><br />';
			$output .= '<table><tr>';
			$output .= '<th></th><th>Quantity</th></tr>';
			foreach( $this->population as $prop)
			{	
				$field = $prop->field_name;
				$readonly = false;
				if ($prop->readonly == "1")
					$readonly = true;
				$output .= '<tr><td>' . $prop->Name . '</td><td><input name="'. $field . '" type="text" size="30"';
				if( $readonly) 
					$output .= ' readonly="readonly"';
				$output .=' value="' . $data->$field . '"></td></tr>';
		
			}
			$output .= '</table>';
			if( $this->currentDay == $this->viewDay)
				$output .= '<input type="submit" name="update" value="Update Number of Cities">';
		}	
		$output .= $this->LoadFooter();		
		return $output;
		
	}
	
	function LoadCentralBank()
	{
		$output = '';
		$output .= $this->LoadCountryHeader(null);
		
		if (isset($_POST['update']))
		{
			foreach($this->entities as $ent)
			{
				if( $ent->Name != "Central Bank")
				{
					$balance_field = $ent->external_bank_field . '_balance';
					$loan_field = $ent->external_bank_field . '_loan';
					$balance_old = $balance_field . '_old';
					$loan_old = $loan_field . '_old';
				
					$new_amt = $_POST[$balance_field];
					$old_amt = $_POST[$balance_old];
					$change_balance = false;
					if( $new_amt != $old_amt)
					{
						//$output .= 'updating central bank table<br />';
						$this->updateValue( "central_bank", $this->currentDay, $balance_field, $new_amt, false );
						$change_balance = true;
					}
					$new_amt = $_POST[$loan_field];
					$old_amt = $_POST[$loan_old];
					$change_loan = false;
					if( $new_amt != $old_amt)
					{
						//$output .= 'updating central bank loan<br />';
						$this->updateValue( "central_bank", $this->currentDay, $loan_field, $new_amt, false );
						$change_loan = true;
					}
					
				
					$externalTable = $ent->bank_table;
					//$output .= 'externalTable = ' . $externalTable . '<br />';

					if(0 && $change_balance)
					{
						$paid_amt = $_POST[$balance_field];
						$update_field = 'central_bank_paid';
						$output .= 'updating central_bank_paid <br />';
						//$output .= 'update field ' . $update_field . ' to ' . $gain_amt . ' <br />';
						$this->updateValue($externalTable, $this->currentDay, $update_field, $paid_amt, false);
					}
					
					if (0 && $change_loan)
					{
						$gain_amt = $_POST[$prop->received_field_name];
						$output .= 'updating central_bank_received <br />';
						$update_field = 'central_bank_received';
						$this->updateValue($additionalTableName, $this->currentDay, $update_field, $paid_amt, false);
					}
				}
			}
		}	
	
		$selection_fields = '';
		foreach ($this->entities as $ent)
		{
			if($ent->Name != "Central Bank")
			{
				$balance_field = $ent->external_bank_field . '_balance';
				$loan_field = $ent->external_bank_field . '_loan';
			
				$selection_fields .= $balance_field . ', ' . $loan_field . ', ';
			}
		}
		$selection_fields = substr($selection_fields, 0, -2);

		$bankData = $this->FinanzesDB->get_row("SELECT " . $selection_fields . " FROM central_bank WHERE day = " . $this->currentDay . " ORDER BY id" );
		if($bankData)
		{	
			$output .= '<form method="post" action="">';
			$output .= '<table>';
			$output .= '<tr><th></th><th>Balance</th><th>Loan</th></tr>';
			foreach( $this->entities as $ent)
			{	
				if($ent->Name != "Central Bank")
				{	
					$balance_field = $ent->external_bank_field . '_balance';
					$loan_field = $ent->external_bank_field . '_loan';
				
					$output .= '<tr><td>' . $ent->Name . '</td><td><input name="'. $balance_field . '" type="text" size="30" value="' . $bankData->$balance_field . '"';
					if( $this->viewDay < $this->currentDay)
						$output .= ' readonly="readonly"';
					$output .='>';
					$output .= '</td><td><input name="'. $loan_field . '" type="text" size="30" value="' . $bankData->$loan_field . '"></td></tr>';
				}
			}
			$output .= '</table>';
			foreach( $this->entities as $ent)
			{	
				if($ent->Name != "Central Bank")
				{	
					$balance_field = $ent->external_bank_field . '_balance';
					$loan_field = $ent->external_bank_field . '_loan';
	
					$loan_old = $loan_field . '_old';
					$balance_old = $balance_field . '_old';

					$output .= '<input type="hidden" name="' . $loan_old . '" value="' . $bankData->$loan_field . '"><input type="hidden" name="' . $balance_old . '" value="' . $bankData->$balance_field . '">';
				}
			}
			if( $this->viewDay == $this->currentDay)
				$output .= '<input type="submit" name="update" value="Update Accounts">';
			$output .= '</form>';
		}
		
	//	$output = '<form method="post" class="utopia" action="">Test: <input type="text" class="utopia" name="tester" value="9"/></form>';
		$output .= $this->LoadFooter();
		return $output;
	
	}
	
	function LoadBankData( $atts )
	{
		extract( shortcode_atts( array(
				'country' => 'Central'), $atts ) );
		
		$output = $this->LoadCountryHeader( $country );
		
		$output .= '<strong>' . $country . ' ' . 'Bank Account Day ' . $this->currentDay . ' </strong><br /><br />';
		$country = strtolower($country);

		$tableName = $country . '_bank';
	
		if (isset($_POST['update']))
		{
			$country = $_POST['country'];
			$tableName = $country . '_bank';
			//$output .= 'tableName is ' . $tableName . '<br />';
			foreach($this->bank_properties as $prop)
			{
				$pos1 = strpos( $prop->pay_field_name, $country);
				//$pos2 = strpos( $prop->pay_field_name, "arms");
				//$pos3 = strpos( $prop->pay_field_name, "real");
				
				if ($pos1 === false )
				{
					$new_amt = $_POST[$prop->pay_field_name];
					$old_field = $prop->pay_field_name . '_old';
					$change_pay = false;
					$old_amt = $_POST[$old_field];
					//$output .= 'new_amt = ' . $new_amt . ' old_field = ' . $old_field . ' old_amt = ' . $old_amt . ' <br />';
					if( $new_amt != $old_amt)
					{
						$change_pay = true;
						//$output .= 'change_pay!<br />';
						$this->updateValue( $tableName, $this->currentDay, $prop->pay_field_name, $new_amt, false );
					}
					
					
					$new_amt = $_POST[$prop->received_field_name];
					$old_field = $prop->received_field_name . '_old';
					$change_received = false;
					$old_amt = $_POST[$old_field];
					//$output .= 'new_amt = ' . $new_amt . ' old_field = ' . $old_field . ' old_amt = ' . $old_amt . ' <br />';
	
					if( $new_amt != $old_amt)
					{
						$change_received = true;
						//$output .= 'change_received!<br />';
						$this->updateValue( $tableName, $this->currentDay, $prop->received_field_name, $new_amt, false );
					}
					
					foreach( $this->entities as $place)
					{
						if( strtolower($place->Name) != $country)
							if( strpos($prop->pay_field_name,$place->external_bank_field) !== false)
							{
								$additionalTableName = $place->bank_table;
								if($change_pay)
								{
									//$output .= 'link ' . $_POST[$prop->pay_field_name] . ' to ' . $place->external_bank_field . '<br />';
									//$output .= 'update table ' . $additionalTableName . '<br />';
									$gain_amt = $_POST[$prop->pay_field_name];
									$update_field = NULL;
									if( $place->Name == "Central Bank")
									{
										$update_field = $country .'_loan';
										$this->updateValue($additionalTableName, $this->currentDay, $update_field, $gain_amt, false);
									}
									else
									{
										$update_field = 'balance';
										$difference = $old_amt-new_amt;
										$this->updateValue($additionalTableName, $this->currentDay, $update_field, $difference, true);
									}

								}
								
								if($change_received)
								{	
									//$output .= 'update field ' . $update_field . ' to ' . $gain_amt . ' <br />';
									$paid_amt = $_POST[$prop->received_field_name];
									$update_field = NULL;
									if( $place->Name == "Central Bank")
									{
										$update_field = $country . '_balance';
										//$output .= 'update field ' . $update_field . ' to ' . $paid_amt . ' <br />';
										$this->updateValue($additionalTableName, $this->currentDay, $update_field, $paid_amt, false);
									}
									else
									{
										$update_field = 'balance';
										$difference = $new_amt - $old_amt;
										$this->updateValue($additionalTableName, $this->currentDay, $update_field, $difference, true);
									}
								}
							}
					}
							
				}
			}
	
		}
		
		
	
		$selection_fields = '';
		foreach ($this->bank_properties as $prop)
		{
			$pos1 = strpos( $prop->pay_field_name, $country);
			//$pos2 = strpos( $prop->pay_field_name, "arms");
			//$pos3 = strpos( $prop->pay_field_name, "real");
				
			if ($pos1 === false )
			{
				//$output .= 'pos = ' . $pos . ' field= ' . $prop->pay_field_name . ' ' ;
				$selection_fields .= $prop->pay_field_name . ', ' . $prop->received_field_name . ', ';
			}			

		}
		if( $this->isCountry($country))
			$selection_fields .= 'initial_balance';
		else
			$selection_fields = substr($selection_fields, 0, -2);

		//$output .= 'selection: ' . $selection_fields . '<br />';
		//$output .= 'country: ' .$country . '<br />';
		$bankData = $this->FinanzesDB->get_row("SELECT " . $selection_fields . " FROM " . $tableName . " WHERE day = " . $this->currentDay . " ORDER BY id" );


		if($bankData)
		{	
			$output .= '<form method="post" action="">';
			$output .= '<input type="hidden" name="country" value="' . $country . '"><br />';
			$output .= '<table>';
			$output .= '<tr><th></th><th>Accounts Payable</th><th>Accounts Received</th></tr>';
			$pay_sum = 0;
			$get_sum = 0;
			$initialBalance = $bankData->initial_balance;
			$output .= '<tr><td>Initial Balance</td><td></td><td><input name="balance" type="text" size="30" readonly="readonly" value="'. $initialBalance . '" /></td></tr>';

			foreach( $this->bank_properties as $prop)
			{	
				if (strtolower($prop->Name) == $country)
					continue;
					
				$paid_field = $prop->pay_field_name;
				$received_field = $prop->received_field_name;
				$pay_sum += $bankData->$paid_field;
				$get_sum += $bankData->$received_field;
				
				$output .= '<tr><td>' . $prop->Name . '</td><td> <input name="'. $paid_field . '" type="text" size="30" value="' . $bankData->$paid_field . '" ';
				if( $this->viewDay < $this->currentDay || $prop->readonly == 1)
					$output .= ' readonly="readonly"';
				$output .=' /></td><td> <input name="'. $received_field . '" type="text" size="30" value="' . $bankData->$received_field . '" />';
				$old_field = $prop->pay_field_name . '_old';
				$output .= '<input name="'. $old_field . '" type="hidden" value="' . $bankData->$paid_field . '">';
				$old_field = $prop->received_field_name . '_old';
				$output .= '<input name="'. $old_field . '" type="hidden" value="' . $bankData->$received_field . '"></td></tr>';
		
			}
			$balance = $initialBalance + $get_sum - $pay_sum;
			if( isset($_POST['update']) )
			{
				$this->updateValue($tableName, $this->currentDay, 'account_balance', $balance, false);
			}
			
			$output .= '<tr><td>Current Balance</td><td></td><td><input name="balance" type="text" size="30" readonly="readonly" value="'. $balance . '" /></td></tr>';
			$output .= '</table>';
			if( $this->viewDay == $this->currentDay)
				$output .= '<input type="submit" name="update" value="Update Accounts">';
			$output .= '</form>';

		}
		$output .= $this->LoadFooter();
		return $output;
	}
	
	function LoadTaxData( $atts )
	{
		extract( shortcode_atts( array(
				'country' => 'Central'), $atts ) );
		$output = '';
		if (isset($_POST['update']))
		{
			$country = $_POST['country'];
			$tableName = $country . '_taxes';
			$selection_fields = '';
			foreach($this->taxes as $prop)
			{
				$is_tax = strpos($prop->field_name, "tax");
				if ( $is_tax !== false)
				{	
					$rate_field = $prop->field_name;
					$new_amt = $_POST[$rate_field];
					$this->updateValue( $tableName, $this->currentDay, $rate_field, $new_amt, false );	
					$selection_fields .= $prop->field_name . ', ';
					$selection_fields .= $prop->money_recd_field_name . ', ';
				
				}
			}

			// results of new rates
			$bankTable = $country . '_bank';
			$this->calculateProduction( $country, $this->currentDay );
			$industryTable = $country . '_industry_log';
			$productionData = $this->FinanzesDB->get_row("SELECT total_production FROM $industryTable WHERE day=$this->currentDay");
			$popTable = $country . '_population_log';
			$num_cities = $this->FinanzesDB->get_var("SELECT cities FROM $popTable WHERE day=$this->currentDay");
			
			
			$selection_fields .= 'total_taxes';
			$taxData = $this->FinanzesDB->get_row("SELECT " . $selection_fields . " FROM " . $tableName . " WHERE day = " . $this->currentDay  );
			
			$income_tax_recd = ($taxData->income_tax_rate/100) * $productionData->total_production;
			$property_tax_recd = ($taxData->property_tax_rate/100) * $num_cities * 10000 * 50000;	
			$sales_tax_recd = ($taxData->sales_tax_rate/100) * $productionData->total_production;
			$total_tax_recd = $income_tax_recd + $property_tax_recd + $sales_tax_recd;
			$this->updateValue($tableName, $this->currentDay, 'income_taxes', $income_tax_recd, false);
			$this->updateValue($tableName, $this->currentDay, 'property_taxes', $property_tax_recd, false);
			$this->updateValue($tableName, $this->currentDay, 'sales_taxes', $sales_tax_recd, false);
			$this->updateValue($tableName, $this->currentDay, 'total_taxes', $total_tax_recd, false);
			$this->updateValue($bankTable, $this->currentDay, 'taxes_received', $total_tax_recd, false);
		
		}
	
		$output .= $this->LoadCountryHeader( $country );
		
		$output .= '<strong>' . $country . ' Taxes Day ' . $this->viewDay . ' </strong><br /><br />';
		$country = strtolower( $country );
		$tableName = $country . '_taxes';
		
		$selection_fields = '';
		
		// make the list of fields to look for
		foreach ($this->taxes as $prop)
		{
			$is_tax= strpos($prop->field_name, "tax");
			if($is_tax !== false)
			{
				$selection_fields .= $prop->field_name . ', ';
				$selection_fields .= $prop->money_recd_field_name . ', ';
			}
		}
		$selection_fields .= 'total_taxes';
		
		$data = $this->FinanzesDB->get_row("SELECT " . $selection_fields . " FROM " . $tableName . " WHERE day = " . $this->viewDay  );
		$readonly = false;
		if( $this->currentDay != $this->viewDay)
			$readonly = true;
		if($data)
		{	
			$output .= '<form method="post" action="">';
			$output .= '<input type="hidden" name="country" value="' . $country . '"><br />';
			$output .= '<table><tr>';
			$output .= '<th></th><th>Rate</th><th>Money Received</th></tr>';
			foreach( $this->taxes as $prop)
			{	
				$is_tax= strpos($prop->field_name, "tax");
				if($is_tax !== false)
				{
					$rate_field = $prop->field_name;
					$money_field = $prop->money_recd_field_name;
					$output .= '<tr><td>' . $prop->Name . '</td><td><input name="'. $rate_field . '" type="text" size="30"';
					if( $readonly)
						$output .= ' readonly="readonly"';
					$output .= ' value="' . $data->$rate_field . '"></td>';
					$output .= '<td><input name="money_received" type="text" size="30" readonly="readonly" value="' . $data->$money_field . '"></td></tr>';
				}	
			}
			$output .= '<tr><td>Total Taxes</td><td></td><td><input name="money_received" type="text" size="30" readonly="readonly" value="' . $data->total_taxes . '"></td></tr>';

			$output .= '</table>';
			if( $this->viewDay == $this->currentDay)
				$output .= '<input type="submit" name="update" value="Update Percentages">';
			$output .= '</form>';
		}
		$output .= $this->LoadFooter();
		return $output;
		
	}

	function LoadGovtProgramData( $atts )
	{
		extract( shortcode_atts( array(
				'country' => 'Central'), $atts ) );

		if (isset($_POST['update']))
		{
			$country = $_POST['country'];
			$tableName = $country . '_govprograms_log';
			$selection_fields = '';
			$tax_selection = '';
			foreach($this->taxes as $prop)
			{
				$is_tax= strpos($prop->field_name, "tax");
				if( $is_tax === false )
				{
					$rate_field = $prop->field_name;
					$new_amt = $_POST[$rate_field];
					$this->updateValue( $tableName, $this->currentDay, $rate_field, $new_amt, false );
					$selection_fields .= $prop->field_name . ', ';
					$selection_fields .= $prop->money_recd_field_name . ', ';
				}
				else
				{
					$tax_selection .= $prop->field_name . ', ';
					$tax_selection .= $prop->money_recd_field_name . ', ';
				}
			}
			$selection_fields .= 'government_total';
			$tax_selection .= 'total_taxes';

			// results of new rates
			$bankTable = $country . '_bank';
			$this->calculateProduction( $country, $this->currentDay );
			$industryTable = $country . '_industry_log';
			$productionData = $this->FinanzesDB->get_row("SELECT total_production FROM $industryTable WHERE day=$this->currentDay");
			$popTable = $country . '_population_log';
			$num_cities = $this->FinanzesDB->get_var("SELECT cities FROM $popTable WHERE day=$this->currentDay");
			$taxTable = $country . '_taxes';			
			
			$taxData = $this->FinanzesDB->get_row("SELECT " . $tax_selection . " FROM " . $taxTable . " WHERE day = " . $this->currentDay  );			
			$income_tax_recd = ($taxData->income_tax_rate/100) * $productionData->total_production;
			$property_tax_recd = ($taxData->property_tax_rate/100) * $num_cities * 10000 * 50000;	
			$sales_tax_recd = ($taxData->sales_tax_rate/100) * $productionData->total_production;
			$total_tax_recd = $income_tax_recd + $property_tax_recd + $sales_tax_recd;
		
			$progData = $this->FinanzesDB->get_row("SELECT " . $selection_fields . " FROM " . $tableName . " WHERE day = " . $this->currentDay  );

			if($progData)
			{
				$money_paid = 0;
				$total_money = 0;
				foreach( $this->taxes as $prop)
				{	
					$is_tax= strpos($prop->field_name, "tax");
					if( $is_tax === false )
					{
						$rate_field = $prop->field_name;		
						$money_field = $prop->money_recd_field_name;
						$this_money = ($progData->$rate_field / 100) * $total_tax_recd;
						$total_money += $this_money;
						if( $prop->field_name == 'health_pct' || $prop->field_name == 'educ_pct')
							$money_paid += $this_money;
						$this->updateValue($tableName, $this->currentDay, $money_field, $this_money, false);
					}
				}
				$this->updateValue($tableName, $this->currentDay, 'government_total', $total_money , false);
				$this->updateValue($bankTable, $this->currentDay, 'govt_programs_paid', $money_paid, false);
			}
		}		
		$output = $this->LoadCountryHeader( $country );
		
		$output .= '<strong>' . $country . ' Government Programs Budget Day ' . $this->viewDay . ' </strong><br /><br />';
		$country = strtolower( $country );
		$tableName = $country . '_govprograms_log';
		
		$selection_fields = '';
		
		// make the list of fields to look for
		foreach ($this->taxes as $prop)
		{
			$is_tax= strpos($prop->field_name, "tax");
			if( $is_tax === false)
			{
				$selection_fields .= $prop->field_name . ', ';
				$selection_fields .= $prop->money_recd_field_name . ', ';
			}
		}
		$selection_fields .= 'government_total';
		
		$data = $this->FinanzesDB->get_row("SELECT " . $selection_fields . " FROM " . $tableName . " WHERE day = " . $this->viewDay  );
		$readonly = false;
		if( $this->currentDay != $this->viewDay)
			$readonly = true;
		if($data)
		{	
			$output .= '<form method="post" action="">';
			$output .= '<input type="hidden" name="country" value="' . $country . '"><br />';
			$output .= '<table><tr>';
			$output .= '<th></th><th>Rate</th><th>Money Used</th></tr>';
			foreach( $this->taxes as $prop)
			{	
				$is_tax= strpos($prop->field_name, "tax");
				if( $is_tax === false)
				{
					$rate_field = $prop->field_name;
					$money_field = $prop->money_recd_field_name;
					$output .= '<tr><td>' . $prop->Name . '</td><td><input name="'. $rate_field . '" type="text" size="30"';
					if( $readonly)
						$output .= ' readonly="readonly"';
					$output .= ' value="' . $data->$rate_field . '"></td>';
					$output .= '<td><input name="money_received" type="text" size="30" readonly="readonly" value="' . $data->$money_field . '"></td></tr>';
				}
			}
			$output .= '<tr><td>Total Money Used</td><td></td><td><input name="money_received" type="text" size="30" readonly="readonly" value="' . $data->government_total . '"></td></tr>';
			$output .= '</table>';
			if( $this->viewDay == $this->currentDay)
				$output .= '<input type="submit" name="update" value="Update Percentages">';
			$output .= '</form>';
		}
		$output .= $this->LoadFooter();
		return $output;
		
	}
	
		
	function LoadConsumptionData( $atts)
	{
		
		extract( shortcode_atts( array(
				'country' => 'Central'), $atts ) );
		
		$output = $this->LoadCountryHeader( $country );
		$output .= '<strong>' . $country . ' Production and Consumption Day ' . $this->viewDay . ' </strong><br /><br />';
		$country = strtolower($country);
		$this->calculateProduction($country, $this->viewDay);
		
		$industryTable = $country  . '_industry_log';
		
		$tableName = $country . '_consumption';
		$selection_fields = '';

		// make the list of fields to look for
		foreach ($this->consumables as $prop)
		{
			$selection_fields .= $prop->field_name . ', ' . $prop->production_field . ', ';
		}
		
		$selection_fields = substr($selection_fields, 0, -2);
		
		$consumptionData = $this->FinanzesDB->get_row("SELECT $selection_fields FROM $tableName,  $industryTable WHERE $tableName.day = $this->viewDay AND $industryTable.day = $this->viewDay" );
		
		
		
		if($consumptionData )
		{	
			$output .= '<form method="post" action="">';
			$output .= '<input type="hidden" name="country" value="' . $country . '"><br />';
			$output .= '<table>';
			$output .= '<tr><th></th><th>Production</th><th>Consumption</th></tr>';
	

			foreach( $this->consumables as $prop)
			{	

				$consumed_field = $prop->field_name;	
				$produced_field = $prop->production_field;	
				$produced_amount = 	$consumptionData->$produced_field;
				$consumed_amount = $consumptionData->$consumed_field;

				$output .= '<tr><td>' . $prop->Name . '</td><td><input name="'. $produced_field . '" type="text" size="30" readonly="readonly" value="' . $produced_amount . '"></td><td><input name="'. $consumed_field . '" type="text" size="30" readonly="readonly" value="' . $consumed_amount . '"></td></tr>';
		
			}
			$output .= '</table>';
			$output .= '</form>';
		}

		$output .= $this->LoadFooter();	
		return $output;
	}
	
	function carryValuesForward( $country, $category, $oldDay, $newDay)
	{
		$tableName = strtolower($country) . '_' . $category . '_log';
		$add_consumption = true;
		$output = ' carry values forward';
		
		if($country == 'arms_trader')
		{
			if ($category == 'industry')
				return;
			$add_consumption = false;
			$tableName = 'arms_trader';
			
		}
		if($country == 'real_estate')
		{
			if ($category == 'army')
				return;
			$add_consumption = false;
			$tableName = 'real_estate_developer';
		}
		$selection_fields = $this->getSelectionFields($country, $category, true);
		if($selection_fields == '')
			return;
				
		
		$output .= 'selecting ' . $selection_fields . ' table is ' . $tableName . ' <br />';

		
		$theData = $this->FinanzesDB->get_row("SELECT " . $selection_fields . " FROM " . $tableName . " WHERE day = " . $oldDay );
		$oldData = $theData;
		if($oldDay != 0)
		{
			$superOldDay = $oldDay -1;
			$oldData = $this->FinanzesDB->get_row("SELECT " . $selection_fields . " FROM " . $tableName . " WHERE day = " . $oldDay );
		}
		$property_name = $category . '_properties';
		$output .= 'carry Value Forward for ' . $country . ' ' . $category . '<br />';
		$output .= 'property_name = ' . $property_name . '<br />';
		$category_properties = $this->$property_name;
		if( $add_consumption  )
			$category_properties = $this->FinanzesDB->get_results("SELECT description, name, plural, field_name, unit, value_per_unit, value_pretty_print, initial_energy_cost, ongoing_energy_cost, initial_construction_cost FROM $property_name");
		
		if($theData)
		{	
			foreach($category_properties as $prop)
			{
				$skip = false;
				if (!$this->fieldInCountry( $prop->field_name, $country))
					$skip = true;
				if( !$skip )
				{
					$output .= ' field: ' . $prop->field_name . '<br />';
					$output .= 'included<br />';
					$field = $prop->field_name;
					if(isset($theData->$field))
					{
						$num_items = $theData->$field;
						$old_items = $oldData->$field;
						$diff = $num_items - $old_items;
						//$pos = strpos( $prop->field_name, "total");
						//if( $pos === false)
							$this->updateValue($tableName, $newDay, $field, $num_items, false);
						//else
						//	$this->updateValue($tableName, $newDay, $field, 0, false);
						if( $field == 'num_soldiers')
						{
							$bankTable = $country . '_bank';
							$soldier_cost = $num_items * 1000;
							$this->updateValue($bankTable, $newDay, 'army_paid', $soldier_cost, false);
						}
						if ($add_consumption)
						{
							//update consumption based on our actions
							$consumptionTable = $country . '_consumption';
							$energy_consumed = $old_items * $prop->ongoing_energy_cost;
							if ($diff > 0)
							{
								$energy_consumed = $prop->initial_energy_cost;
								$construction_used = $prop->initial_construction_cost;
								$this->updateValue($consumptionTable, $newDay, 'const_mat_consumed', $construction_used, true);

							}
							
							$this->updateValue($consumptionTable, $newDay, 'total_energy_consumed', $energy_consumed, true);
						}
					}
				}
			}
			if( $this->isCountry($country))
			{
				// carry forward the balance		
				$old_bal = $theData->balance;
				$this->updateValue($tableName, $newDay, 'balance', $old_bal, false);
						
			}
		}
		//return $output;
	}
	
	function initializeBank( $country, $oldDay, $newDay)
	{
		$tableName = strtolower($country) . '_bank';
		if($country == 'arms_trader')
			$tableName = 'arms_trader';
		if($country == 'real_estate')
			$tableName = 'real_estate_developer';
		
		$selection_fields = '';
		


		
		foreach ($this->bank_properties as $prop)
		{
			$pos1 = strpos( $prop->pay_field_name, $country);
				
			if ($pos1 === false )
			{
				if( $country != 'arms_trader' && $country != 'real_estate')
				{
					$this->updateValue($tableName, $newDay, $prop->pay_field_name, 0, false);
					$this->updateValue($tableName, $newDay, $prop->received_field_name, 0, false);
				}
			}			
		}
		
		if( $this->isCountry( $country ))
		{
			$oldBalance = $this->FinanzesDB->get_var("SELECT account_balance FROM $tableName WHERE day=$oldDay");
			$this->updateValue( $tableName, $newDay, 'initial_balance', $oldBalance, false);
		}
	}
	
	
	function updateTaxesAndGovtProgs( $country, $oldDay, $newDay)
	{
		$taxTable = $country . '_taxes';
		$output = '';		
		$selection_fields = '';
		// make the list of fields to look for
		foreach ($this->taxes as $prop)
		{
			$is_tax= strpos($prop->field_name, "tax");
			if($is_tax !== false)
			{
				$selection_fields .= $prop->field_name . ', ';
				$selection_fields .= $prop->money_recd_field_name . ', ';
			}
		}
		$selection_fields .= 'total_taxes';
		$taxData = $this->FinanzesDB->get_row("SELECT " . $selection_fields . " FROM " . $taxTable . " WHERE day = " . $oldDay  );
		
		
		$selection_fields = '';
		$popTable = $country .'_population_log';
		foreach ($this->population as $prop)
		{
			$selection_fields .= $prop->field_name . ', ';
		}
		
		$selection_fields = substr($selection_fields, 0, -2);
		
		$popData = $this->FinanzesDB->get_row("SELECT " . $selection_fields . " FROM " . $popTable . " WHERE day = " . $oldDay  );
		
		
		$industryTable = $country . '_industry_log';
		$productionData = $this->FinanzesDB->get_row("SELECT total_production FROM $industryTable WHERE day=$oldDay");


		$income_tax_recd = ($taxData->income_tax_rate/100) * $productionData->total_production;
		$property_tax_recd = ($taxData->property_tax_rate/100) * $popData->cities * 10000 * 50000;	
		$sales_tax_recd = ($taxData->sales_tax_rate/100) * $productionData->total_production;
		$total_tax_recd = $income_tax_recd + $property_tax_recd + $sales_tax_recd;

		$bankTable = $country . '_bank';
		
		foreach($this->taxes as $prop)
		{
			$is_tax= strpos($prop->field_name, "tax");
			if($is_tax !== false)
			{		
				$rate_field = $prop->field_name;
				$this->updateValue($taxTable, $newDay, $rate_field, $taxData->$rate_field, false); 
			}
		}
		
		$this->updateValue($taxTable, $newDay, 'income_taxes', $income_tax_recd, false);
		$this->updateValue($taxTable, $newDay, 'property_taxes', $property_tax_recd, false);
		$this->updateValue($taxTable, $newDay, 'sales_taxes', $sales_tax_recd, false);
		$this->updateValue($taxTable, $newDay, 'total_taxes', $total_tax_recd, false);
		$this->updateValue($bankTable, $newDay, 'taxes_received', $total_tax_recd, false);


		$progTable = $country . '_govprograms_log';
		$selection_fields = '';
		foreach($this->taxes as $prop)
		{
			$is_tax= strpos($prop->field_name, "tax");
			if($is_tax === false)
			{		
				$selection_fields .= $prop->field_name . ', ';
				$selection_fields .= $prop->money_recd_field_name . ', ';
			}
		}
		$selection_fields .= 'government_total';

		$progData = $this->FinanzesDB->get_row("SELECT " . $selection_fields . " FROM " . $progTable . " WHERE day = " . $oldDay  );
		if($progData)
		{
			$money_paid = 0;
			$total_money = 0;
			foreach( $this->taxes as $prop)
			{	
				$is_tax= strpos($prop->field_name, "tax");				
				if( $is_tax === false )
				{
					$rate_field = $prop->field_name;		
					$money_field = $prop->money_recd_field_name;
					$this_money = ($progData->$rate_field / 100) * $total_tax_recd;
					$total_money += $this_money;
					if( $prop->field_name == 'health_pct' || $prop->field_name == 'educ_pct')
						$money_paid += $this_money;
					$this->updateValue($progTable, $newDay, $money_field, $this_money, false);
					$this->updateValue($progTable, $newDay, $rate_field, $progData->$rate_field, false);
				}
			}
			$output .= 'setting gov_total in ' . $progTable . ' to ' . $total_money . 'for day ' . $newDay . '<br />';
			$output .= 'setting gov_programs_total in ' . $bankTable . ' to ' . $money_paid . '<br />';
			$this->updateValue($progTable, $newDay, 'government_total',  $total_money, false);
			$this->updateValue($bankTable, $newDay, 'govt_programs_paid', $money_paid , false);			
		}
	return $output;		
	}
		
/*	function updateTaxes($country, $oldDay, $newDay)
	{
		$taxTable = $country . '_taxes';
		$selection_fields = '';
		
		// make the list of fields to look for
		foreach ($this->population as $prop)
		{
			$selection_fields .= $prop->field_name . ', ';
		}
		
		$selection_fields = substr($selection_fields, 0, -2);
		
		$data = $this->FinanzesDB->get_row("SELECT " . $selection_fields . " FROM " . $tableName . " WHERE day = " . $this->viewDay  );
		if($data)
		{	
			foreach( $this->population as $prop)
			{	
				$field = $prop->field_name;
				
				switch($prop->field_name)
				{
					case "income_tax_rate":
					case "sales_tax_rate":
					case "property_tax_rate":
						$this->updateValue($popTable, $newDay, $prop->field_name, $data->$field);
						break;
					case "income_taxes":
					case "sales_taxes":
					case "property_taxes":				
						break;
					case "total_taxes":
						break;
				}
			}
		}			
	}
*/
	
	function calculateProduction( $country, $day)
	{
		$industryTable = $country . '_industry_log';
				
		$industrySelection = $this->getSelectionFields( $country, 'industry', false);
		$industry_data = $this->FinanzesDB->get_row("SELECT " . $industrySelection . " FROM " . $industryTable .  " WHERE day= " . $day );
		$construction_produced = $industry_data->num_constructionmaterialproducers * 3000;
		$oil_produced = $industry_data->num_oilwells * 150000;
		$alternate_energy = $industry_data->num_alternativeenergy * 150000;
		$energy_produced = $oil_produced + $alternate_energy;
		$water_produced = $industry_data->num_waterproducers * 50000000;
		$food_produced = $industry_data->num_foodproducers * 75000;
		$total_production = ($construction_produced*5) + ($energy_produced*100) + ($water_produced*.007) + ($food_produced*3);
		
		$this->updateValue($industryTable, $day, 'construction_produced', $construction_produced, false);
		$this->updateValue($industryTable, $day, 'oil_produced', $oil_produced, false);
		$this->updateValue($industryTable, $day, 'alt_energy_produced', $alternate_energy, false);
		$this->updateValue($industryTable, $day, 'water_produced', $water_produced, false);
		$this->updateValue($industryTable, $day, 'food_produced', $food_produced, false);
		$this->updateValue($industryTable, $day, 'total_production', $total_production, false);
		
		$energy_consumed = ($food_produced * .1) + ($water_produced * .01);
		$consumptionTable = $country . '_consumption';
		$this->updateValue($consumptionTable, $day, 'total_energy_consumed', $energy_consumed, true);
		
	/*	if($day == 0)
		{
			$popTable = $country . '_population_log';
			$populationData = $this->FinanzesDB->get_row("SELECT population, unemployment_rate FROM $popTable WHERE day=0");
			$employable = $populationData->population / 2;
			$unemployed = $employable * ($populationData->unemployment_rate/100);
			$employed = $employable - $unemployed;
			$liveable_wage = $total_production / $employed;
			$output .= $country . 'Total Production=' . $total_production . '<br />';
			$output .= $country . 'Population=' . $populationData->population . '<br />';
			$output .= $country . 'Unemployment Rate=' . $populationData->unemployment_rate. '<br />';
			$output .= $country . 'Number Employed=' . $employed. '<br />';
			$output .= '<strong> So ' . $country . 'Liveable Wage=' . $liveable_wage. '</strong><br />';
			
			$homeTable = $country . '_home';
			$this->updateValue($homeTable, $day, 'liveable_wage', $liveable_wage, false);
		}*/
		
	}
	
	function updatePopulationAndConsumption($country, $oldDay, $newDay)
	{
			
		$popTable = $country . '_population_log';
		$industryTable = $country . '_industry_log';
		$selection_fields = '';
		$homeTable = $country . '_home';
		$liveable_wage = $this->FinanzesDB->get_var("SELECT liveable_wage FROM $homeTable WHERE day=0");
		$productionData = $this->FinanzesDB->get_row("SELECT total_production, alt_energy_produced FROM $industryTable WHERE day=$oldDay");
		
		$output = '';
		
		// make the list of fields to look for
		foreach ($this->population as $prop)
		{
			$selection_fields .= $prop->field_name . ', ';
		}
		
		$selection_fields = substr($selection_fields, 0, -2);
		
		$data = $this->FinanzesDB->get_row("SELECT " . $selection_fields . " FROM " . $popTable . " WHERE day = " . $oldDay  );
		
		if($data)
		{	
			$old_pop = $data->population;
			$rate = $data->pop_growth_rate/100;
			$new_pop = $old_pop * (1.0+($rate/12));
			$employable = $old_pop * .5;
			$unemployed = $employable * ($data->unemployment_rate/100);
			$employed = $employable - $unemployed;
			$net_immigration = 0;
			$food_consumed = $old_pop * 3;
			$water_consumed = $old_pop;
			$old_cities = $data->cities;
			
			if( strtolower($country) == 'filios')
			{
				$intelibus_unemployment = $this->FinanzesDB->get_var("SELECT unemployment_rate FROM intelibus_population_log WHERE day= ".$oldDay );
				$num_attempted = $intelibus_unemployment * 100;
				$num_caught = $num_attempted * .2;
				$net_immigration = $num_attempted - $num_caught;
				$food_consumed = $old_pop * 4;
				$water_consumed = $old_pop * 50;
				//$output .= 'filios gained ' . $net_immigration . ' people immigrating <br />';
			}
			if( strtolower($country) == 'intelibus')
			{
				$num_attempted = $data->unemployment_rate * 100;
				$num_returned = $num_attempted * .2;
				$net_immigration = $num_returned - $num_attempted; // this should be negative
				$water_consumed = 0;
				//$output .= 'intelibus lost ' .$net_immigration . ' people <br />';
				
			}
			
			$consumptionTable = $country . '_consumption';
			$this->updateValue($consumptionTable, $newDay, 'food_consumed', $food_consumed, true);
			$this->updateValue($consumptionTable, $newDay, 'water_consumed', $water_consumed, true);
			
			
			//$output .= $country . 'unemployment rate = '. $data->unemployment_rate . ' so there are ' .$employed . 'people working, and ' . $unemployed . ' people unemployed.  Population is ' . $data->population . '<br />';
			foreach( $this->population as $prop)
			{	
				$field = $prop->field_name;
				
				switch($prop->field_name)
				{
					case "population":
						$this->updateValue($popTable, $newDay, $prop->field_name, $new_pop, false);
						break;
					case "num_immigrated":
						$this->updateValue($popTable, $newDay, $prop->field_name, $net_immigration, false);
						break;
					case "unemployment_rate":
						$unemployment_rate = ($unemployed/$employable) * 100;
						//$output .= 'unemployment rate = ' . $unemployment_rate . '<br />';
						$this->updateValue($popTable, $newDay, $prop->field_name, $unemployment_rate, false);
						break;
					case "salaries":
						$salaries = $productionData->total_production / $employed;
						$this->updateValue($popTable, $newDay, $prop->field_name, $salaries, false);
						break;
					case "pop_growth_rate":						
						$this->updateValue($popTable, $newDay, $prop->field_name, $data->$field, false);
						break;
					case "cities":
						$new_cities = $data->$field;
						$energy_consumed = 10000 * $old_cities;
						$cities_built = $new_cities - $old_cities;
						$consumptionTable = $country . '_consumption';
						if ($cities_built > 0)
						{
							$construction_used = $cities_built * 1000000;
							$this->updateValue($consumptionTable, $newDay, 'const_mat_consumed', $construction_used, true);

							$energy_consumed += $cities_built * 500000;
						}
						
						$this->updateValue($consumptionTable, $newDay, 'total_energy_consumed', $energy_consumed, true);
						$this->updateValue($popTable, $newDay, $prop->field_name, $data->$field, false);
						break;
				}
			}
		}	
		return $output;		
	}
	
	function isCountry($name)
	{
		if(strtolower($name)=='xtensica' || strtolower($name)=='filios' || strtolower($name)=='intelibus')
			return true;
		return false;
	}
	
	function closeDay()
	{
		$newDay = $this->currentDay +1;

		// army_properties
		$output = '<strong>close Day ' . $this->currentDay .'</strong><br />';
		foreach($this->entities as $ent)
		{
			$skip = false;
			$country = strtolower($ent->Name);
			
			if( $ent->Name == 'Arms Trader')
			{
				$category = $country = 'arms_trader';
			}
			else if( $ent->Name == 'Real Estate Developer')
			{
				$category = $country = 'real_estate';
			}
			else if( $ent->Name == 'Central Bank')
			{
				$selection_fields = '';
				foreach ($this->entities as $ent)
				{
					if($ent->Name != "Central Bank")
					{
						$balance_field = $ent->external_bank_field . '_balance';
						$loan_field = $ent->external_bank_field . '_loan';
			
						$selection_fields .= $balance_field . ', ' . $loan_field . ', ';
					}
				}
				$selection_fields = substr($selection_fields, 0, -2);

				$bankData = $this->FinanzesDB->get_row("SELECT " . $selection_fields . " FROM central_bank WHERE day = " . $this->currentDay . " ORDER BY id" );
				if($bankData)			
				{
					foreach( $this->entities as $ent2)
					{	
						if($ent2->Name != "Central Bank")
						{	
							$balance_field = $ent2->external_bank_field . '_balance';
							$loan_field = $ent2->external_bank_field . '_loan';
										
							$this->updateValue('central_bank', $newDay, $balance_field, $bankData->$balance_field, false);
							$this->updateValue('central_bank', $newDay, $loan_field, $bankData->$loan_field, false);
						}
					}
				}
				$skip = true;
			}
			else 
			{ // this stuff applies only to countries
				$output .= $this->initializeBank($country, $this->currentDay, $newDay);	
				$output .= $this->calculateProduction($country, $this->currentDay);
				$output .= $this->updatePopulationAndConsumption( $country, $this->currentDay, $newDay);
				$output .= $this->updateTaxesAndGovtProgs($country, $this->currentDay, $newDay);
			}
			
			
			if(!$skip)
			{ // this stuff applies to all entities but central bank
				$traders = array('army', 'industry');
				foreach($traders as $category)
				{
					$output .= $this->carryValuesForward( $country, $category ,$this->currentDay, $newDay);
				}
			}
			
			if ( $this->isCountry( $country) )
			{
				$consumptionTable = $country . '_consumption';
				$industryTable = $country . '_industry_log';
				// divide up the energy usage
				$energy_used = $this->FinanzesDB->get_var( "SELECT total_energy_consumed FROM $consumptionTable WHERE day = $newDay");
				$alt_produced = $this->FinanzesDB->get_var( "SELECT alt_energy_produced FROM $industryTable WHERE day = $newDay");
				$alt_used = 0;
				$oil_used = 0;
				if( $energy_used > $alt_produced)
				{
					$oil_used = $energy_used - $alt_produced;
					$alt_used = $alt_produced;
				}
				else
				{
					$oil_used = 0;
					$alt_used = $energy_used;
				}
				$this->updateValue($consumptionTable, $newDay, 'oil_consumed', $oil_used, false);
				$this->updateValue($consumptionTable, $newDay, 'alt_energy_consumed', $alt_used, false);
			}
				
		}
		//return $output;
	}
		
	function GameHome( $country, $day )
	{	
		$output = '';
		if( isset($_POST['EnterNotes']) )
		{
			for($ii = 0; $ii < $_POST['notesCount']; $ii++)
			{
				$noteIdVar = 'Scene' . $ii;
				$sceneId = $_POST[$noteIdVar];
				$noteName = 'Note' . $ii;
				
				$this->FinanzesDB->update('scenarios', array('Notes' => $_POST[$noteName]), 
													array('id'=> $sceneId), 
													array('%s'), array('%d'));
			}
			$this->scenarios = $this->FinanzesDB->get_results("SELECT id, Title, scene, Notes FROM scenarios WHERE day=" . $this->viewDay );

		}
		if( isset($_POST['endDay']) )
		{
			$output .= $this->closeDay();
			$newDay = $this->currentDay + 1;
			$this->FinanzesDB->query(" UPDATE game_state SET current_day = $newDay, viewing_day = $newDay WHERE id = 1");
			
			$this->InitDB();
		}
		if( isset($_POST['viewPast']) )
		{
			$this->FinanzesDB->query(" UPDATE game_state SET viewing_day = " . $_POST['viewDay'] . " WHERE id = 1");
			$this->InitDB();
			
		}
		if( !$dbInit )
			$this->InitDB();
			
		$output .= '<strong>Welcome to Almost Utopia<br />';
		
		if( $this->currentDay != $this->viewDay)
			$output .= 'Viewing Past Day ';
		else
			$output .= 'Today is Day ';
		$output .=   $this->viewDay . '</strong><br />';
		$output .='<table><tr><th>Visit A Place:</th><th></th></tr>';
	
		$output .= '<tr><td border-width="0px"><a href=../xtensica/> Xtensica </a></td>';
		$output .= '<td><a href=../central-bank/> Central Bank </a></td></tr>';
	
		$output .= '<tr><td><a href=../filios/> Filios </a></td>';
		$output .= '<td><a href=../arms-trader/> Arms Trader </a></td></tr>';
		
		$output .= '<tr><td><a href=../intelibus/> Intelibus </a></td>';
		$output .= '<td><a href=../real-estate-developer/> Real Estate Developer </a></td></tr>';
	
		$output .= '<tr><td></td><td><a href=../commodity-values/>Commodity Values</a></td></tr></table>';
		
		
		$output .= '<br /><strong>Events</strong><br />';
		$output .= '<form method="post" action="">';
		if( $this->scenarios)
		{
			$sceneCount = 0;
			foreach( $this->scenarios as $event )
			{
				$output .= '<br />' . $event->Title;
				$output .= '<br />' . $event->scene;
				$varName = 'Scene' . $sceneCount;
				$output .= '<input type="hidden" name="' . $varName . '" value="'. $event->id . '" />';
				$textAreaName = 'Note' . $sceneCount;
				$output .= '<textarea name="' . $textAreaName . '" cols="25" rows="5"';
				if( $this->currentDay != $this->viewDay)
					$output .= ' readonly="readonly" ';
				$output .= '>';
				$output .= $event->Notes;
				$output .= '</textarea><br>';
				$output .= '<input type="submit" value="Enter Notes" name="EnterNotes" />';
				$sceneCount++;
			}
			$output .= '<input type="hidden" name="notesCount" value = "' . $sceneCount . '" />';
		}
	

//		$output .= '<br /><br /><table><tr><td>';		
//		if( $this->currentDay == $this->viewDay )
	//		$output .= '<input type="submit" name="endDay" value="End Day ' . $this->currentDay . '" >';

	//	$output .= '</td><td>';
//		$output .= 'View Past Day <select name="viewDay">';
	//	for( $day=$this->currentDay; $day >= 0; $day--)
//			$output .= '<option value=' . $day . '>'.$day.'</option>';
//		$output .='</select><input type="submit" name="viewPast" value="View Day" ></td></tr></table>';
		
	//	$output .= '</form><br />';
		
		return $output;
	}	
			
	function LoadCountryHeader( $country )
	{
		$output = '';
		if( isset($_POST['viewPast']) )
		{
			$this->FinanzesDB->query(" UPDATE game_state SET viewing_day = " . $_POST['viewDay'] . " WHERE id = 1");
			$this->InitDB();
			
		}	
		if($country == null)
			return null;
			
		$output .= '<table><tr>';
		$output .= '<td><a href="../' . $country .'/"> ' . $country . ' Home </a></td>';
		$output .= '<td><a href="../' . $country .'-army/"> Army </a></td>';
		$output .= '<td><a href="../' . $country .'-industry/"> Industry </a></td>';
		$output .= '<td><a href="../' . $country .'-population/"> Population </a></td>';
		$output .= '<td><a href="../' . $country .'-bank/"> Bank Account </a></td>';
		$output .= '<td><a href="../' . $country .'-tax/"> Taxes </a></td>';
		$output .= '<td><a href="../' . $country .'-government-programs/"> Government Programs Budget </a></td>';
		$output .= '<td><a href="../' . $country .'-consumption/"> Consumption </a></td>';
		$output .= '<td><a href="../' . $country .'-diplomat/"> Diplomat </a></td>';
				
		$output .= '</tr></table>';	
		return $output;
	}

	
	function LoadFooter( )
	{
	
		$output = '';
//		$output .= '<form method="post" action="">';
//		$output .= '<br /><br /><table><tr><td>';		
//		$output .= 'View Past Day <select name="viewDay">';
//		for( $day=$this->currentDay; $day >= 0; $day--)
//			$output .= '<option value=' . $day . '>'.$day.'</option>';
//		$output .='</select><input type="submit" name="viewPast" value="View Day" ></td></tr></table>';
		
//		$output .= '</form><br />';
			
		$output .= '<table><tr>';
		$output .= '<td><a href="../"> Almost Utopia Home </a></td>';
		$output .= '<td><a href=../xtensica/> Xtensica </a></td>';
		$output .= '<td><a href=../filios/> Filios </a></td>';
		$output .= '<td><a href=../intelibus/> Intelibus </a></td>';
		$output .= '<td><a href=../central-bank/> Central Bank </a></td>';	
		$output .= '<td><a href=../arms-trader/> Arms Trader </a></td>';		
		$output .= '<td><a href=../real-estate-developer/> Real Estate Developer </a></td></tr>';	
		$output .= '</table>';	

		return $output;
	}	 
	
	
	function LoadCountryPage( $atts )
	{
		extract( shortcode_atts( array(
				'country' => 'Central'), $atts ) );
		$output = '';
		
		$output .= $this->LoadCountryHeader( $country );
		
		$output .= '<strong>Welcome to ' . $country . '.  Day ' . $this->viewDay;
		$readonly = false;
		if ($this->viewDay == $this->currentDay)
		{
			$output .= ' (today).<br />';
			$readonly = false;
		}
		else
		{
			$output .= ' (has ended). <br />';
			$readonly = true;
		}
		$printCountry = $country;
		$country = strtolower($country);
		$tableName = $country . '_home';
				
		
		if( isset($_POST['weather']) )
		{ 
			if($_POST['weather'] == "Set Weather")
			{
				$this->updateValue( $tableName, $this->currentDay, "weather_id", $_POST["weatherSelect"], false);    
			}
			else if( $_POST['weather'] == "Randomly Choose Weather")
			{
				$randWeather = rand(1, count( $this->weathers));
				$this->updateValue( $tableName, $this->currentDay, "weather_id", $randWeather, false);
				$readonly = true;
			}
		}
		$dayWeather = '';
		$dayWeather = $this->FinanzesDB->get_var("SELECT description FROM available_weather, $tableName WHERE available_weather.id = $tableName.weather_id AND $tableName.day  = $this->viewDay");		
		
		$output .= '<form method="post" action="">';
		$output .= 'Weather Report <br />';

	/*	$output .= '<select name="weatherSelect">';
		foreach( $this->weathers as $w)
		{
			$output .= '<option value='. $w->id ;
			if( $w->id == $dayWeather)
				$output .= ' selected ';
			$output .= '>' . $w->description . '</option>';
		}
		$output .= '</select>';
	*/		
		$output .= '<input type="text" name="weather-input" length="25" readonly="readonly" value="' . $dayWeather . '">';
		if ($readonly == false && $dayWeather == '')
		{
		//	$output .= '<input type="submit" name="weather" value="Set Weather">';
			$output .= '<input type="submit" name="weather" value="Randomly Choose Weather"><br />';
		}		
		$output .= '</form>';
		
//		$liveable_wage = $this->FinanzesDB->get_var("SELECT liveable_wage FROM $tableName WHERE day=0");

//		$output .= '<br /><br /> The minimum liveable wage in '. $printCountry . ' is $' . $liveable_wage . ' per month. <br />'; 
		
		
		$output .= $this->LoadFooter();
		return $output;						
	} 
		
}

 
$bank = new Finanzes;
 
 
function parameter_queryvars( $qvars )
{
$qvars[] = 'country';
$qvars[] = 'day';
return $qvars;
}

function get_country()
{
global $wp_query;
if (isset($wp_query->query_vars['country']))
{
return $wp_query->query_vars['country'];
}
}	

function get_day()
{
global $wp_query;
if (isset($wp_query->query_vars['day']))
{
return $wp_query->query_vars['day'];
}
}	

?>