/*
  Infix to postfix conversion in Php
  Input Postfix expression must be in a desired format. 
  Only '+'  ,  '-'  , '*', '/' and '%' (for exponentiation)  operators are expected. 
  Only 'sin'  ,  'cos'  , 'tan' math function  are expected. 
*/


<?php
error_reporting(E_ERROR | E_PARSE);
ini_set("display_errors", 0);

//////////////////////input Expression/////////////////////////
$text=$_POST['text'];

/////////////////main class //////////////////////////////////////
class Calculators
{
	function Calcualate($expression)
	{
		if($expression[0]=='*' || $expression[0] =='+' ||  $expression[0] =='/' ||  $expression[0] =='^' ||  $expression[0] =='%')
			return("Error : At first you can't enter operator!");
		else
			return($this->postfix_exp_Calculate($this->infix_to_postfix($expression)));
		
	}
	///////////////////////////////calcualte postfix expression////////////////////////
	function postfix_exp_Calculate($postfix_exp)
	{
		$result = array();
		$i=0;
		$lengh=sizeof($postfix_exp);
		for($i=0;$i<$lengh;$i++)
		{
			$item=$postfix_exp[$i];
			if($this->is_operator($item))
			{
				switch($item)
				{
					case "+":
						$second=array_pop($result);
						$first=array_pop($result);
						array_push($result,$first+$second);
						break;
					case "-":
						$second=array_pop($result);
						$first=array_pop($result);
						array_push($result,$first-$second);
						break;
					case "*":
						$second=array_pop($result);
						$first=array_pop($result);
						array_push($result,$first*$second);
						break;
					case "/":
						$second=array_pop($result);
						$first=array_pop($result);
						array_push($result,$first/$second);
						break;
					case "%":
						$second=array_pop($result);
						$first=array_pop($result);
						array_push($result,$first%$second);
						break;
					case "^":
						$second=array_pop($result);
						$first=array_pop($result);
						$power=pow($first,$second);
						array_push($result,$power);
				}	
			}
			else
				array_push($result , $item);
		}
		return($result[0]);
	}
	////////////////////////////////infix to postfix////////////////////////////////
	function infix_to_postfix($expression)
	{
			$precedence = array
			(
			'(' => 0,
			'-' => 3,
			'+' => 3,
			'*' => 6,
			'/' => 6,
			'%' => 6,
			'^' => 8
			);
		$final_stack = new SplStack();
		$operator_stack = new SplStack();
		$i=0;
		while($i<strlen($expression))
		{		
			$char = $expression[$i];
			if($char=='=')
			{
				$i++;
				break;
			}
//////////////////////////sin////////////////////////////
			if($char=='s')
			{
				$g=0;
				$exp="";
				$i+=4;
				$char=$expression[$i];
				while(($char!=')'&&($f==0))||($f!=0))
				{
					if($char=='(')$f++;
					if($f!=0&&($char==')'))$f--;
					if($char=='=')
					{
						$i++;
						break;
					}
					else
					{
						$exp.=$char;
						$i++;	
					}
					$char=$expression[$i];
					
				}
				$i++;
				$result=$this->Calcualate($exp);
				$result=sin(deg2rad($result));
				$final_stack->push($result);
				continue;
			}
//////////////////////////cos////////////////////////////
			if($char=='c')
			{
				$g=0;
				$exp="";
				$i+=4;
				$char=$expression[$i];
				while(($char!=')'&&($f==0))||($f!=0))
				{
					if($char=='(')$f++;
					if($f!=0&&($char==')'))$f--;
					if($char=='=')
					{
						$i++;
						break;
					}
					else
					{
						$exp.=$char;
						$i++;	
					}
					$char=$expression[$i];
					
				}
				$i++;
				$result=$this->Calcualate($exp);
				$result=cos(deg2rad($result));
				$final_stack->push($result);
				continue;
			}
//////////////////////////tan////////////////////////////
			if($char=='t')
			{
				$g=0;
				$exp="";
				$i+=4;
				$char=$expression[$i];
				while(($char!=')'&&($f==0))||($f!=0))
				{
					if($char=='(')$f++;
					if($f!=0&&($char==')'))$f--;
					if($char=='=')
					{
						$i++;
						break;
					}
					else
					{
						$exp.=$char;
						$i++;	
					}
					$char=$expression[$i];
					
				}
				$i++;
				$result=$this->Calcualate($exp);
				$result=tan(deg2rad($result))
					;
				$final_stack->push($result);
				continue;
			}
//////////////////////////number////////////////////////////
			if($this->is_number($char))
			{
				$num = $this->readnumber($expression,$i);
				$final_stack->push($num);
				$i+=strlen($num);
				continue;
			}	
//////////////////////////operator////////////////////////////
			if($this->is_operator($char))
			{
				if($char == '-')
				{
					$mineschar=$expression[$i-1];
					if($this->is_operator($mineschar) ||(!$mineschar) || $mineschar=='(')
					{
						$num = $this->readnumber($expression,$i+1);
						$num="-".$num;
						$final_stack->push($num);
						$i+=strlen($num);
						continue;
					}
				}
				$top ="";
				if(!$operator_stack->isEmpty())
				{
					$top=$operator_stack->pop();
					$operator_stack->push($top);
				}		
				while($top && ($precedence[$char] <= $precedence[$top]))
				   {
					if(!$operator_stack->isEmpty())
					{					
						$upper = $operator_stack->pop();
						$final_stack->push($upper);
					}
					if(!$operator_stack->isEmpty())
					{					
						$top = $operator_stack->pop();
						$operator_stack->push($top);	
					}
					if($operator_stack->isEmpty())
						   break;
				   }
				   $operator_stack->push($char);
				   $i++;
				   continue;
			}
//////////////////////////Parenthesis////////////////////////////
			if($char == '(')
			{
				$operator_stack->push($char);
				$i++;
				
				continue;
			}
//////////////////////////Parenthesis////////////////////////////
			if($char == ')')
			{	
				
				$operator="";
				do
				{
					if(!$operator_stack->isEmpty())
					{
					$operator = $operator_stack->pop();
					if($operator == '(')break;
					$final_stack->push($operator);
					}
				}while($operator);
				$i++;					
			}
		}//////end while
		
	////////////////////////stack 's ordering//////////////////////////////
		while(!$operator_stack->isEmpty())
		{
			$operator = $operator_stack->pop();
			$final_stack->push($operator);
		}	
		$arr=array();
		$i=0;
		while(!$final_stack->isEmpty())
		{
			$g = $final_stack->pop();
			$operator_stack->push($g);
			$i++;	
		}
		$i=0;
		/////////////stack to array///////////////////
		while(!$operator_stack->isEmpty())
		{
			
			$arr[$i]=$operator_stack->pop();
			$i++;
		}
		return($arr);
	}
	//////////////////////////is operator function////////////////////////////
	function is_operator($char) 
	{
		static $operators = array('+', '-', '/', '*', '%', '^');
		return in_array($char, $operators);
	}
	//////////////////////////is number function////////////////////////////
	function is_number($char) 
	{
		return (($char == '.') || ($char >= '0' && $char <= '9'));
	}
	//////////////////////////readnumber function////////////////////////////
	function readnumber($str, $i) 
	{
		$string=$str;
		$number = '';
		$s="";
		for($j=0;$j<strlen($string);$j++)
		{
			$char=$string[$j];
			$char.=" ";
			$s.=$char;
		}
		$s=explode(" ",$s);
		while ($this->is_number($s[$i]))
			$number .= $s[$i++];		
		return $number;
	}
}
?>