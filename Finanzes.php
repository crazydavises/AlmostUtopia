<?php
/*
Plugin Name: FinanzesFilios DataTracking
Plugin URI: http://testing.designdavis.com
Description: FinanzesFilios Game
Version: 0.1
Author: Amy Davis
Author URI: http://growingliketrees.blogspot.com
*/


global $finanzasfilios_db_version;
$infanzasfilios_db_version = 1.0;

class Finanzes
{		
	var $currentDay;
		
	function __construct()
	{
		add_action( 'init', array( $this, 'InitDB' ) ); 		
		add_action( 'wp_head', array($this, 'addHeaderCode') );
		add_shortcode( 'GameHome', array( $this, 'GameHome' ) );
		add_shortcode( 'LoadCountry ', array( $this, 'LoadCountryPage' ) );
		add_shortcode( 'Trader', array( $this, 'LoadTraderData' ) );
		add_shortcode( 'Bank', array( $this, 'LoadBankData' ) );
		add_shortcode( 'CentralBank', array( $this, 'LoadCentralBank') );
		add_shortcode( 'Consumption', array( $this, 'LoadConsumptionData') );
		add_shortcode( 'Population', array( $this, 'LoadPopulationData') );
		add_shortcode( 'Taxes', array( $this, 'LoadTaxData') );
		add_shortcode( 'GovernmentPrograms', array( $this, 'LoadGovtProgramData') );

	}
	
	function InitDB()
	{
		$output = '';
		$this->FinanzesDB = new wpdb('testingheidi', 'truelovewaits', 'finanzesfilios', 'mysql.testing.designdavis.com');
	    $this->FinanzesDB->show_errors();
	
		$dayStats = $this->FinanzesDB->get_row( "SELECT current_day, viewing_day FROM game_state");
		
		$this->currentDay = $dayStats->current_day;
		$this->viewDay = $dayStats->viewing_day;
			
			$output .= 'now currentDay = ' . $this->currentDay . '<br />';
		//$this->currentDay++;
		
		$this->army_properties = $this->FinanzesDB->get_results("SELECT description, name, plural, field_name, unit, cost_per_unit, loss_unit, loss_print, cost_pretty_print FROM army_properties");
		$this->industry_properties = $this->FinanzesDB->get_results("SELECT description, name, plural, field_name, unit, cost_per_unit, cost_pretty_print FROM industry_properties");
		$this->bank_properties = $this->FinanzesDB->get_results("SELECT Name, pay_field_name, received_field_name FROM bank_properties");
		$this->entities = $this->FinanzesDB->get_results("SELECT Name, bank_table, external_bank_field, internal_paid, internal_received, aggregate, link FROM entities");
		$this->consumables = $this->FinanzesDB->get_results("SELECT Name, field_name, units_pretty_print, formula FROM consumption_properties");
		$this->taxes = $this->FinanzesDB->get_results("SELECT Name, field_name, money_recd_field_name FROM tax_types");
		$this->population = $this->FinanzesDB->get_results("SELECT Name, field_name, readonly FROM population_properties");
		$this->scenarios = $this->FinanzesDB->get_results("SELECT Title, scene FROM scenarios WHERE day=" . $this->viewDay );
		$this->weathers = $this->FinanzesDB->get_results("SELECT id, description FROM available_weather");
		$this->arms_trader_properties = $this->FinanzesDB->get_results("SELECT description, name, plural, field_name, unit, cost_per_unit, cost_pretty_print FROM arms_trader_properties");
		$this->real_estate_properties = $this->FinanzesDB->get_results("SELECT description, name, plural, field_name, unit, cost_per_unit, cost_pretty_print FROM real_estate_properties");
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

			if($cur_data->$field != $new_quant)
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
		if( $country == 'arms_trader')
			$category = 'arms_trader';
		if( $country == 'real_estate')
			$category = 'real_estate';
		
		$property_name = $category . '_properties';
	
		foreach ($this->$property_name as $prop)
		{
			if (!$this->fieldInCountry( $prop->field_name, $country))
				$selection_fields = $selection_fields; // no change 
			else
				$selection_fields .= $prop->field_name . ', ';
		}
		

		if ($includeTotals)
			$selection_fields .= 'total_' . $category . 'costs';
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
	function LoadTraderData( $atts)
	{
		extract( shortcode_atts( array(
				'country' => 'Filios',
				'category' => 'Army'), $atts ) );
		$output = '';
		if ($country != 'Arms' && $country != 'Real Estate')
			$output = $this->LoadCountryHeader( $country );
		
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
					$item = substr( $rest, 0, -5);
					break;
			}
			
			$item_field = '';
			$old_num = 0;
			foreach($this->$property_name as $prop)
			{
				if ($prop->name == $item)
				{
					$item_field = $prop->field_name;
					break;
				}
			}
			
			//$output .= ' operation = ' . $operation . ' field = ' . $item_field . ' item = ' . $item . '<br />';
			$old_num = $theData->$item_field;
			$new_num = $old_num;
			$balance_field = 'total_' . $category . 'costs';
			
			//$output .= 'old_num = ' . $old_num . ' balance field = ' . $balance_field . ' on table ' . $tableName . '<br />';
				

			if( $operation == "sell" || $operation == "lose" || $operation == "use")
			{
				$new_num -= $prop->unit;
				$diff = $new_num - $old_num;	
				$cost = $diff * $prop->cost_per_unit;		
				//$output .= 'diff = ' . $diff . ' cost = ' . $cost . '<br />';

				$this->updateValue( $tableName, $this->currentDay, $item_field, $new_num, false );
				if( $operation == "sell")
					$this->updateValue( $tableName, $this->currentDay, $balance_field, $cost, true );
			
			}
			else if ($operation == "buy")
			{
				$new_num += $prop->unit;
				$diff = $new_num - $old_num;			
				$cost = $diff * $prop->cost_per_unit;		
				//$output .= 'diff = ' . $diff . ' cost = ' . $cost . '<br />';
				
				$this->updateValue( $tableName, $this->currentDay, $item_field, $new_num, false );
				$this->updateValue( $tableName, $this->currentDay, $balance_field, $cost, true );

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
						$total_item_price = $num_items * $prop->cost_per_unit;	
						$unit_item_string = $prop->unit . ' ' . $prop->name;
						$lost_item_string = $unit_item_string . ' Lost';
						if( isset( $prop->loss_unit))
							$lost_item_string = $prop->loss_unit . ' ' . $prop->name . ' ' . $prop->loss_print;  
						$output .= '<tr><td><strong>' . $prop->description .' ' . $num_items . ' ' .  $prop->plural . '</strong>,';
						$output .= $prop->cost_pretty_print . 'Total: $' . $total_item_price . '</td>';
			
						if($this->viewDay == $this->currentDay)
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
				$balance_field = 'total_' . $category . 'costs';
				$amt = 	$theData->$balance_field;
				$output .= '<tr><td> ' . $category . ' ';
				if( $amt < 0 )
				{
					$output .= 'Gain ';
					$amt *= -1;
				}
				else
					$output .= 'Costs ' ;
				$output .= '<input name="abs_costs" type="text" readonly="readonly" size="30" value="' . $amt . '"></td></tr>';
				$output .= '</table>';

			}
			
		}

		$output .= '</form>';
		
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
				$total_item_price = $num_items * $prop->cost_per_unit;	
				$unit_item_string = $prop->unit . ' ' . $prop->pretty_name;
				
				$output .= '<tr><td><strong>' . $prop->description .' ' . $num_items . ' ' .  $prop->name . 's</strong>,';
				$output .= $prop->cost_pretty_print . 'Total: $' . $total_item_price . '</td>';
			
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
		
		return $output;
	}
	

	function LoadPopulationData( $atts )
	{
		extract( shortcode_atts( array(
				'country' => 'Central'), $atts ) );
		
		
		if ($_POST['update'])
		{
			$country = $_POST['country'];
			$day = $_POST['day'];
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
			$output .= '<input type="hidden" name="day" value="' . $day . '"><br />';
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
			
		return $output;
		
	}
	
	function LoadCentralBank()
	{
		$output = '';
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
									$update_field = $place->internal_received;
									if ($update_field == "NULL")
										$update_field = $country . '_received';
									if( $place->Name == "Central Bank")
										$update_field = $country .'_loan';
									$this->updateValue($additionalTableName, $this->currentDay, $update_field, $gain_amt, false);

								}
								
								if($change_received)
								{	
									//$output .= 'update field ' . $update_field . ' to ' . $gain_amt . ' <br />';
									$paid_amt = $_POST[$prop->received_field_name];
									$update_field = $place->internal_paid;
									if ($update_field == "NULL")
										$update_field = $country . '_paid';
									if( $place->Name == "Central Bank")
										$update_field = $country . '_balance';
									
									//$output .= 'update field ' . $update_field . ' to ' . $paid_amt . ' <br />';
									$this->updateValue($additionalTableName, $this->currentDay, $update_field, $paid_amt, false);
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
		$selection_fields = substr($selection_fields, 0, -2);

	//	$output .= 'selection: ' . $selection_fields . '<br />';
	//	$output .= 'country: ' .$country . '<br />';
		$bankData = $this->FinanzesDB->get_row("SELECT " . $selection_fields . " FROM " . $tableName . " WHERE day = " . $this->currentDay . " ORDER BY id" );


		if($bankData)
		{	
			$output .= '<form method="post" action="">';
			$output .= '<input type="hidden" name="country" value="' . $country . '"><br />';
			$output .= '<table>';
			$output .= '<tr><th></th><th>Accounts Received</th><th>Accounts Payable</th></tr>';
			foreach( $this->bank_properties as $prop)
			{	
				if (strtolower($prop->Name) == $country)
					continue;
					
				$paid_field = $prop->pay_field_name;
				$received_field = $prop->received_field_name;
				
				$output .= '<tr><td>' . $prop->Name . '</td><td> <input class="bogus" name="'. $paid_field . '" type="text" size="30" value="' . $bankData->$paid_field . '" />';
//				if( $this->viewDay < $this->currentDay)
//					$output .= ' readonly="readonly"';
				$output .='</td><td> <input class="bogus" name="'. $received_field . '" type="text" size="30" value="' . $bankData->$received_field . '" />';
//				$old_field = $prop->pay_field_name . '_old';
//				$output .= '<input name="'. $old_field . '" type="hidden" value="' . $bankData->$paid_field . '">';
//				$old_field = $prop->received_field_name . '_old';
//				$output .= '<input name="'. $old_field . '" type="hidden" value="' . $bankData->$received_field . '"></td></tr>';
		
			}
			$output .= '</table>';
			if( $this->viewDay == $this->currentDay)
				$output .= '<input type="submit" name="update" value="Update Accounts">';
			$output .= '</form>';

		}
		
		return $output;
	}
	
	function LoadTaxData( $atts )
	{
		extract( shortcode_atts( array(
				'country' => 'Central'), $atts ) );
		$output = '';
		if ($_POST['update'])
		{
			$country = $_POST['country'];
			$day = $_POST['day'];
			$tableName = $country . '_taxes';
			
			foreach($this->taxes as $prop)
			{
				$is_tax = strpos($prop->field_name, "tax");
				if ( $is_tax !== false)
				{	
					$rate_field = $prop->field_name;
					$new_amt = $_POST[$rate_field];
					$this->updateValue( $tableName, $this->currentDay, $rate_field, $new_amt, false );
				}
			}
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
			$output .= '<input type="hidden" name="day" value="' . $day . '"><br />';
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
		return $output;
		
	}

	function LoadGovtProgramData( $atts )
	{
		extract( shortcode_atts( array(
				'country' => 'Central'), $atts ) );

		if ($_POST['update'])
		{
			$country = $_POST['country'];
			$day = $_POST['day'];
			$tableName = $country . '_govprograms_log';
			
			foreach($this->taxes as $prop)
			{
				$is_tax= strpos($prop->field_name, "tax");
				if($is_tax === false)
				{		
					$rate_field = $prop->field_name;
					$new_amt = $_POST[$rate_field];
					$this->updateValue( $tableName, $this->currentDay, $rate_field, $new_amt, false );
				}
			}
		}		
		$output = $this->LoadCountryHeader( $country );
		
		$output .= '<strong>' . $country . ' Government Programs Day ' . $this->viewDay . ' </strong><br /><br />';
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
			$output .= '<input type="hidden" name="day" value="' . $day . '"><br />';
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
		
		return $output;
		
	}
	
		
	function LoadConsumptionData( $atts)
	{
		
		extract( shortcode_atts( array(
				'country' => 'Central'), $atts ) );
		
		$output = $this->LoadCountryHeader( $country );
		
		$output .= '<strong>' . $country . ' Consumption Day ' . $this->viewDay . ' </strong><br /><br />';
		$country = strtolower($country);
		$category = strtolower($category);
		
		$tableName = $country . '_consumption';
		
		$selection_fields = '';
		
		// make the list of fields to look for
		foreach ($this->consumables as $prop)
		{
			$selection_fields .= $prop->field_name . ', ';
		}
		$selection_fields = substr($selection_fields, 0, -2);
		
		$data = $this->FinanzesDB->get_row("SELECT " . $selection_fields . " FROM " . $tableName . " WHERE day = " . $this->viewDay . " ORDER BY id" );
		
		if($data)
		{	
			$output .= '<form method="post" action="">';
			$output .= '<input type="hidden" name="day" value="' . $day . '"><br />';
			$output .= '<input type="hidden" name="country" value="' . $country . '"><br />';
			$output .= '<table>';
	
			foreach( $this->consumables as $prop)
			{	

				$field = $prop->field_name;				
				$output .= '<tr><td>' . $prop->Name . '</td><td><input name="'. $field . '" type="text" size="30" readonly="readonly" value="' . $data->$field . '"></td></tr>';
		
			}
			$output .= '</table>';
			$output .= '</form>';
		}	
		return $output;
	}
	
	function carryValuesForward( $country, $category, $oldDay, $newDay)
	{
		$tableName = strtolower($country) . '_' . $category . '_log';
		if($country == 'arms_trader')
			$tableName = 'arms_trader';
		if($country == 'real_estate')
			$tableName = 'real_estate_developer';
			
		$selection_fields = $this->getSelectionFields($country, $category, false);
		if($selection_fields == '')
			return;
			
		$theData = $this->FinanzesDB->get_row("SELECT " . $selection_fields . " FROM " . $tableName . " WHERE day = " . $oldDay );
		$property_name = $category . '_properties';
		$output = 'carry Value Forward for ' . $country . ' ' . $category . '<br />';
		$output .= 'property_name = ' . $property_name . '<br />';
		if($theData)
		{	
			foreach($this->$property_name as $prop)
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
						$pos = strpos( $prop->field_name, "total");
						if( $pos === false)
							$this->updateValue($tableName, $newDay, $field, $num_items, false);
						else
							$this->updateValue($tableName, $newDay, $field, 0, false);
					}
				}
			}
		}
		return $output;
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
	}
	
	
	function closeDay()
	{
		$newDay = $this->currentDay +1;
		// army_properties
		$output = 'close Day ' . $this->currentDay .'<br />';
		foreach($this->entities as $ent)
		{
			$country = strtolower($ent->Name);
			if( $ent->Name == 'Arms Trader')
			{
				$category = $country = 'arms_trader';
			}
			if( $ent->Name == 'Real Estate Developer')
			{
				$category = $country = 'real_estate';
			}
			$skip = false;
			if( $ent->Name == 'Central Bank')
			{
				// do central bank stuff
				$skip = true;
			}
			
			if (!$skip)
			{
				$traders = array('army', 'industry');
				foreach($traders as $category)
				{
					$output .= $this->carryValuesForward( $country, $category ,$this->currentDay, $newDay);
				}
				$output .= $this->initializeBank($country, $this->currentDay, $newDay);

			}	
		}
		return $output;
	}
		
	function GameHome( $country, $day )
	{	
		$output = '';
		if( isset($_POST['endDay']) )
		{
			$this->closeDay();
			$newDay = $this->currentDay + 1;
			$output .= 'new day is ' . $newDay . '<br />';
			$this->FinanzesDB->query(" UPDATE game_state SET current_day = $newDay, viewing_day = $newDay WHERE id = 1");
			
			$output .= $this->InitDB();
		}
		if( isset($_POST['viewPast']) )
		{
			$this->FinanzesDB->query(" UPDATE game_state SET viewing_day = " . $_POST['viewDay'] . " WHERE id = 1");
			$this->InitDB();
			
		}
		
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
		$output .= '<td><a href=../real-estate-developer/> Real Estate Developer </a></td></tr></table>';
	
		$output .= '<br /><strong>Events</strong><br />';
		if( $this->scenarios)
		{
			foreach( $this->scenarios as $event )
			{
				$output .= '<br />' . $event->Title;
				$output .= '<br />' . $event->scene . '<br />';
			}
		}
	
		$output .= '<form method="post" action="">';
		$output .= '<br /><br /><table><tr><td>';		
		if( $this->currentDay == $this->viewDay )
			$output .= '<input type="submit" name="endDay" value="End Day ' . $this->currentDay . '" >';

		$output .= '</td><td>';
		$output .= 'View Past Day <select name="viewDay">';
		for( $day=$this->currentDay; $day >= 0; $day--)
			$output .= '<option value=' . $day . '>'.$day.'</option>';
		$output .='</select><input type="submit" name="viewPast" value="View Day" ></td></tr></table>';
		
		$output .= '</form><br />';
		
		return $output;
	}	
			
	function LoadCountryHeader( $country )
	{
		$output = '';
			
		$output .= '<table><tr>';
		$output .= '<td><a href="../"> Almost Utopia Home </a></td>';
		$output .= '<td><a href="../' . $country .'/"> ' . $country . ' Home </a></td>';
		$output .= '<td><a href="../' . $country .'-army/"> Army </a></td>';
		$output .= '<td><a href="../' . $country .'-industry/"> Industry </a></td>';
		$output .= '<td><a href="../' . $country .'-population/"> Population </a></td>';
		$output .= '<td><a href="../' . $country .'-bank/"> Bank Account </a></td>';
		$output .= '<td><a href="../' . $country .'-tax/"> Taxes </a></td>';
		$output .= '<td><a href="../' . $country .'-government-programs/"> Government Programs </a></td>';
				$output .= '<td><a href="../' . $country .'-consumption/"> Consumption </a></td>';
		$output .= '</tr></table>';	
		return $output;
	}
	 
	function LoadCountryPage( $atts )
	{
		extract( shortcode_atts( array(
				'country' => 'Central'), $atts ) );
		$output = '';
		
		$output .= $this->LoadCountryHeader( $country );
		
		$output .= '<strong>Welcome to ' . $country . '.  Day ' . $this->viewDay;
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
			}
		}

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
		$output .= '<input type="text" name="weather-input" length="25" readonly="' . $readonly . '" value="' . $dayWeather . '">';
		if ($readonly == false)
		{
		//	$output .= '<input type="submit" name="weather" value="Set Weather">';
			$output .= '<input type="submit" name="weather" value="Randomly Choose Weather"><br />';
		}		
		$output .= '</form>';
		
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
