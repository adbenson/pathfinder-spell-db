<?php

$cssA = new CSS();
$cssA->load('print.css');

$cssB = new CSS();
$cssB->load('all.css');

//print_r($cssA->styles);
$cond = $cssB->condenseRules()->condenseSelectors();
echo $cond->toString();

//$diff = $cssA->diff($cssB);

//$diff->condense();

//echo $diff->toString();

class CSS {

	public $styles = array();
		
	/**
	 *	Merges declarations where all selectors match
	 */
	public function condenseSelectors() {
		return $this->condense('selectors', 'declarations');
	}
	
	/**
	 *	Merges rules where all declarations match
	 */
	public function condenseRules() {
		return $this->condense('declarations', 'selectors');
	}
	
	private function condense($match, $merge) {
		$condensed = new CSS();
	
		foreach($this->styles as $rule) {
			
			$matched = false;
			
			foreach($condensed->styles as &$cond) {
				if($rule[$match] === $cond[$match]) {
					array_merge($cond[$merge], $rule[$merge]);
					$matched = true;
					break;
				}
			}
			
			if (! $matched) {
				array_push($condensed->styles, $rule);
			}
		}

		return $condensed;
	}
		
	public function toString($showDiff = true) {
		$css = '';
		
		foreach($this->styles as $rule) {		
			$css .= implode(",\n", $rule['selectors']);
			$css .= " {\n";
			
			foreach($rule['declarations'] as $def) {
				$css .= "\t".$def['property'].": ".$def['value'].";";
				if ($showDiff && array_key_exists('diff', $def)) {
					$css .= " \t/* ".$def['diff'].' */';
				}
				$css .= "\n";
			}
			
			$css .= "}\n\n";
		}
		
		return $css;
	}
	
	function sortedDiff(CSS $cssB) {
		$a = $this->styles;
		$b = $cssB->styles;
		
		$diffA = array();
		$diffB = array();
		
		$ruleA = array_shift($a);
		$ruleB = array_shift($b);
		
		do { 		
			$compare = strcmp(implode($ruleA['selectors']), implode($ruleB['selectors']));
			
			//Selectors match, compare deeper.
			if ($compare === 0) {
				$declarationsA = $ruleA['declarations'];
				$declarationsB = $ruleB['declarations'];
				
				$diffDeclA = array();
				$diffDeclB = array();

				$declA = array_shift($declarationsA);
				$declB = array_shift($declarationsB);
				
				do {
					$declCompare = strcmp($declA['property'], $declB['property']);
					
					if ($declCompare < 0) {
						array_push($diffA, $ruleA);
					}
					if ($declCompare > 0) {
						array_push($diffB, $ruleB);
					}
					
					//Advance the stacks
					if ($declCompare <= 0) {
						$ruleA = array_shift($a);
					}
					if ($declCompare >= 0) {
						$ruleB = array_shift($b);
					}
					
				} while (! empty($declarationsA) && ! empty($declarationsB));
				
				array_merge($diffA, $a);
				array_merge($diffB, $b);
			}
			
			//Push unmatched selectors
			if ($compare < 0) {
				array_push($diffA, $ruleA);
			}
			if ($compare > 0) {
				array_push($diffB, $ruleB);
			}
			
			//Advance the stacks
			if ($compare <= 0) {
				$ruleA = array_shift($a);
			}
			if ($compare >= 0) {
				$ruleB = array_shift($b);
			}			
		
		} while(! empty($a) && ! empty($b));
		
		array_merge($diffA, $a);
		array_merge($diffB, $b);
	}
	
	function diff(CSS $b) {
		$a = $this;
		
		$diff = new CSS();
		
		foreach($a->styles as $ruleA) {
			
			$selectorsA = $ruleA['selectors'];
			
			$matchedSelector = false;
			
			foreach($b->styles as $ruleB) {
				$matchedSelector = $ruleB['selectors'];
			
				if ($selectorsA === $selectorsB) {
					$matched = true;
					
					$declarationsA = $ruleA['declarations'];
					
					foreach($declarationsA as $declA) {
					
						foreach($declarationsB as $declB) {
						
							if ($declA['property'] === $declB['property']) {
							
							}
						}
					}
				}
			}
			
			if (! $matchedSelector) {
				array_push($diff->styles, $ruleA);
			}
			
		}
		
		return $diff;		
	}
	
	private function keyDiff($arrayA, $arrayB, $key) {
		$diff = array();
	
		foreach($arrayA as $elementA) {
			
			$keyA = $elementA[$key];
			
			$matched = false;
			
			foreach($arrayB as $elementB) {
				
				$keyB = $elementB[$key];
				
				if ($keyA === $keyB) {
					$matched = true;
					
					
				}				
			}
			
			if (! $matched) {
				array_push($diff, $elementA);
			}		
		}
		
		return $diff;	
	}
	
	public function load($file, $sort = true) {
		$contents = file_get_contents($file);
				
		//REmove any contiguous whitespaces
		$contents = preg_replace('/\s\s*/', ' ', $contents);
		
		//Remove all comments (/*  */) or (/** */)
		//Matches: '/', '*', optional '*', any number of:(not '*', or '*' not followed by '/'), '*', '/'
		$contents = preg_replace('/\/\*\*?([^\*]|\*(?!\/))*\*\//', '', $contents);
		
		$rules = explode('}', $contents);
		
		foreach($rules as $rule) {
			$rule = explode('{', $rule);
			
			$selector = trim($rule[0]);
			
			if (empty($selector) || $selector[0] === '@') {
				continue;
			}
			
			$declarations = explode(';', $rule[1]);		
			
			foreach($declarations as $i => $decl) {
			
				$decl = trim($decl);
				
				//Clear out empty lines
				if (empty($decl)) {
					unset($declarations[$i]);
				}
				else {
					$decl = explode(':', $decl);
				
					$declarations[$i] = array(
						'property' => trim($decl[0]),
						'value' => trim($decl[1])
					);
				}

			}
			
			if ($sort) {
				usort($declarations, function($a, $b) {
					return strcmp($a['property'], $b['property']);
				});
			}
			
			$selectorSet = preg_split('/,\s*/', $selector);
			
			array_push($this->styles, array(
				'selectors' => $selectorSet,
				'declarations' => $declarations
			));
		}
		
		if ($sort) {
			usort($this->styles, function($a, $b) {
				$selectorA = implode('', $a['selectors']);
				$selectorB = implode('', $b['selectors']);
				
				return strcmp($selectorA, $selectorB);
			});
		}
		
		return $this;
		
	}

}

?>