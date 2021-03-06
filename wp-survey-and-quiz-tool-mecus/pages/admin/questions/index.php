<script type="text/javascript">
jQuery(document).ready(function() {
	jQuery("#wpsqt_questions tbody.wpsqt_questions_content").sortable();
	jQuery("#wpsqt_questions tbody.wpsqt_questions_content").disableSelection();
});

var saveOrder = function() {
	var table = jQuery("#wpsqt_questions tbody.wpsqt_questions_content");
	var order = "";
	table.children().each(function() {
		order = order + jQuery(this).attr('id') + ',';
	});
	order = encodeURIComponent(order);
	orderURL = "<?php echo WPSQT_URL_MAIN.'&section=questions&subsection=survey&id='.$_GET['id'].'&order=' ?>" + order;
	window.location = orderURL;
}
</script>

<div class="wrap">

	<div id="icon-tools" class="icon32"></div>
	<h2>WP Survey And Quiz Tool - Questions</h2>
		
	<?php require WPSQT_DIR.'pages/admin/misc/navbar.php'; ?>
	
	<?php if ( isset($_GET['new']) &&  $_GET['new'] == "true" ) { ?>
	<div class="updated">
		<strong>Question successfully added.</strong>
	</div>
	<?php } ?>
	
	<?php if ( isset($_GET['edit']) &&  $_GET['edit'] == "true" ) { ?>
	<div class="updated">
		<strong>Question successfully edited.</strong>
	</div>
	<?php } ?>
	
	<?php if ( isset($_GET['delete']) &&  $_GET['delete'] == "true" ) { ?>
	<div class="updated">
		<strong>Question successfully deleted.</strong>
	</div>
	<?php } ?>
	<ul class="subsubsub">
		<?php foreach ( $question_types as $type ){ 
				$friendlyType = str_replace(' ', '', $type);
			?>			
			<li>
				<a href="<?php echo WPSQT_URL_MAIN; ?>&section=questions&subsection=<?php echo urlencode($_GET['subsection']); ?>&type=<?php echo $type; ?>" <?php if (isset($_GET['type']) && $type == $_GET['type']) { ?>  class="current"<?php } ?>><?php echo $type; ?> <span class="count">(<?php echo $question_counts[$friendlyType.'_count']; ?>)</span></a>
			</li>
		<?php } ?>
	</ul>
	<div class="tablenav">
	
		
	
		<?php if ( isset($_GET['id']) ){ ?>
		<div class="alignleft">
			<a href="<?php echo WPSQT_URL_MAIN; ?>&section=questionadd&subsection=<?php esc_html_e($_GET['subsection']); ?>&id=<?php esc_html_e($_GET['id']); ?>" class="button-secondary" title="Add New Question">Añade una nueva pregunta</a>
			<a href="#" title="Save Order" onclick="saveOrder();" class="button-secondary">Guarda el orden</a>
		</div>
		<?php } ?>		
		<div class="tablenav-pages">
		   <?php echo Wpsqt_Core::getPaginationLinks($currentPage, $numberOfPages);  ?>
		</div>
	</div>
	<table class="widefat" id="wpsqt_questions">
		<thead>
			<tr>
				<th>ID</th>
				<th>Pregunta</th>
				<th>Tipo</th>
				<th>Porcentaje</th>
				<th>Votos</th>
				<th>Editar</th>
				<th>Borrar</th>
				<th></th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<th>ID</th>
				<th>Pregunta</th>
				<th>Tipo</th>
				<th>Porcentaje</th>
				<th>Votos</th>
				<th>Editar</th>
				<th>Borrar</th>
				<th></th>
			</tr>
		</tfoot>
		<tbody class="wpsqt_questions_content">
			<?php if ( empty($questions) ) { ?>			
				<tr>
					<td colspan="5"><div style="text-align: center;">¡Todavía no hay preguntas!</div></td>
				</tr>
			<?php }
				  else {
				  	$count = 0;
				  	$geigercounter = 0;
					foreach ($questions as $rawQuestion) { 
						$count++;
						$question = Wpsqt_System::unserializeQuestion($rawQuestion, $_GET['subsection']);
						?>
			<tr class="<?php echo ( $count % 2 ) ?  'wpsqt-odd' : 'wpsqt-even'; ?>" id="<?php echo $question['id']; ?>">
				<td><?php echo $question['id']; ?></td>
				<td><?php echo stripslashes($question['name']); ?></td>
				<td><?php echo ucfirst( stripslashes($question['type']) ); ?></td>
                
				<td>
					<?php    
                    
                    //global $wpdb;
                    $correct_answer = 0;
                    $total_votes_per_question = 0;
                    $answer_0_ask = array();
                    $answer_1_ask = array();
                    $answer_2_ask = array();
                    $answer_3_ask = array();
                    $answer_4_ask = array();
                    $answer_5_ask = array();
                    $answer_6_ask = array();
                    $answer_7_ask = array();
                    $answer_8_ask = array();
                    $answer_9_ask = array();
                    $answer_10_ask = array();
                    $answer_0 = 0;
                    $answer_1 = 0;
                    $answer_2 = 0;
                    $answer_3 = 0;
                    $answer_4 = 0;
                    $answer_5 = 0;
                    $answer_6 = 0;
                    $answer_7 = 0;
                    $answer_8 = 0;
                    $answer_9 = 0;
                    $answer_10 = 0;
                    
                    
                    $RESULTS_count = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM %s", WPSQT_TABLE_RESULTS ) );
                    $lastID = $wpdb->get_var( $wpdb->prepare( "SELECT id FROM %s ORDER BY `id` DESC LIMIT 0,1", WPSQT_TABLE_RESULTS ) );
                    
                    for($i = 1; $i <= $lastID; $i++){
                    
                        $rawResult = $wpdb->get_row(
                                        $wpdb->prepare("SELECT * FROM ".WPSQT_TABLE_RESULTS." WHERE id = $i"),ARRAY_A);
                                                        
                        $rawResult['sections'] = unserialize($rawResult['sections']);
                                                
                        foreach((array)$rawResult['sections'] as $result_sections){
                            if(isset($result_sections['answers'][$question['id']]['mark']) && ($result_sections['answers'][$question['id']]['mark'] == 'correct')){ $correct_answer++; }
                            if(isset($result_sections['answers'][$question['id']]['given'])){ $total_votes_per_question++; }
                            if ( $result_sections['answers'][$question['id']]['given'][0] == '0' ) { $answer_0++; } 
                            if ( $result_sections['answers'][$question['id']]['given'][0] == '1' ) { $answer_1++; } 
                            if ( $result_sections['answers'][$question['id']]['given'][0] == '2' ) { $answer_2++; } 
                            if ( $result_sections['answers'][$question['id']]['given'][0] == '3' ) { $answer_3++; } 
                            if ( $result_sections['answers'][$question['id']]['given'][0] == '4' ) { $answer_4++; } 
                            if ( $result_sections['answers'][$question['id']]['given'][0] == '5' ) { $answer_5++; } 
                            if ( $result_sections['answers'][$question['id']]['given'][0] == '6' ) { $answer_6++; } 
                            if ( $result_sections['answers'][$question['id']]['given'][0] == '7' ) { $answer_7++; } 
                            if ( $result_sections['answers'][$question['id']]['given'][0] == '8' ) { $answer_8++; } 
                            if ( $result_sections['answers'][$question['id']]['given'][0] == '9' ) { $answer_9++; } 
                            if ( $result_sections['answers'][$question['id']]['given'][0] == '10' ) { $answer_10++; } 
                            
                            $elid = $count-1;
                            if ( !isset( $answer_0_ask[$count] )) { $answer_0_ask[$count] = $result_sections['questions'][$elid]['answers'][0]['text']; }
                            if ( !isset( $answer_1_ask[$count] )) { $answer_1_ask[$count] = $result_sections['questions'][$elid]['answers'][1]['text']; }
                            if ( !isset( $answer_2_ask[$count] )) { $answer_2_ask[$count] = $result_sections['questions'][$elid]['answers'][2]['text']; }
                            if ( !isset( $answer_3_ask[$count] )) { $answer_3_ask[$count] = $result_sections['questions'][$elid]['answers'][3]['text']; }
                            if ( !isset( $answer_4_ask[$count] )) { $answer_4_ask[$count] = $result_sections['questions'][$elid]['answers'][4]['text']; }
                            if ( !isset( $answer_5_ask[$count] )) { $answer_5_ask[$count] = $result_sections['questions'][$elid]['answers'][5]['text']; }
                            if ( !isset( $answer_6_ask[$count] )) { $answer_6_ask[$count] = $result_sections['questions'][$elid]['answers'][6]['text']; }
                            if ( !isset( $answer_7_ask[$count] )) { $answer_7_ask[$count] = $result_sections['questions'][$elid]['answers'][7]['text']; }
                            if ( !isset( $answer_8_ask[$count] )) { $answer_8_ask[$count] = $result_sections['questions'][$elid]['answers'][8]['text']; }
                            if ( !isset( $answer_9_ask[$count] )) { $answer_9_ask[$count] = $result_sections['questions'][$elid]['answers'][9]['text']; }
                            if ( !isset( $answer_10_ask[$count] )) { $answer_10_ask[$count] = $result_sections['questions'][$elid]['answers'][10]['text']; }
                        }
                        
                    }
										if ( isset ( $answer_0_ask[$count] ) ) { echo $answer_0_ask[$count] . ' ' . $answer_0 . '<br />'; }
										if ( isset ( $answer_1_ask[$count] ) ) { echo $answer_1_ask[$count] . ' ' . $answer_1 . '<br />'; }
										if ( isset ( $answer_2_ask[$count] ) ) { echo $answer_2_ask[$count] . ' ' . $answer_2 . '<br />'; }
										if ( isset ( $answer_3_ask[$count] ) ) { echo $answer_3_ask[$count] . ' ' . $answer_3 . '<br />'; }
										if ( isset ( $answer_4_ask[$count] ) ) { echo $answer_4_ask[$count] . ' ' . $answer_4 . '<br />'; }
										if ( isset ( $answer_5_ask[$count] ) ) { echo $answer_5_ask[$count] . ' ' . $answer_5 . '<br />'; }
										if ( isset ( $answer_6_ask[$count] ) ) { echo $answer_6_ask[$count] . ' ' . $answer_6 . '<br />'; }
										if ( isset ( $answer_7_ask[$count] ) ) { echo $answer_7_ask[$count] . ' ' . $answer_7 . '<br />'; }
										if ( isset ( $answer_8_ask[$count] ) ) { echo $answer_8_ask[$count] . ' ' . $answer_8 . '<br />'; }
										if ( isset ( $answer_9_ask[$count] ) ) { echo $answer_9_ask[$count] . ' ' . $answer_9 . '<br />'; }
										if ( isset ( $answer_10_ask[$count] ) ) { echo $answer_10_ask[$count] . ' ' . $answer_10 . '<br />'; }

                    
                    $success_rate = number_format(($correct_answer/$RESULTS_count)*100, 0);
                    //echo ($success_rate);
                    echo ($success_rate<80 ? "<span style=\"color:red;\">$success_rate%</span>" : "<span style=\"color:green;\">$success_rate%</span>");
                    ?>
                </td>
                <td>Total: <?php echo $total_votes_per_question; ?></td>
                
				<td><a href="<?php echo WPSQT_URL_MAIN; ?>&section=questionedit&subsection=<?php esc_html_e($_GET['subsection']); ?>&id=<?php esc_html_e($_GET['id']); ?>&questionid=<?php esc_html_e($question['id']); ?>" class="button-secondary" title="Edit Question">Edit</a></td>
				<td><a href="<?php echo WPSQT_URL_MAIN; ?>&section=questiondelete&subsection=<?php esc_html_e($_GET['subsection']); ?>&id=<?php esc_html_e($_GET['id']); ?>&questionid=<?php esc_html_e($question['id']); ?>" class="button-secondary" title="Delete Question">Delete</a></td>
				<td><img src="<?php echo plugin_dir_url(WPSQT_DIR.'images/handle.png').'handle.png'; ?>" /></td>
			</tr>
			
			
			
			
			<?php } 
				 }?>
		</tbody>
	</table>

	<div class="tablenav">
		<?php if ( isset($_GET['id']) ){ ?>
		<div class="alignleft">
			<a href="<?php echo WPSQT_URL_MAIN; ?>&section=questionadd&subsection=<?php esc_html_e($_GET['subsection']); ?>&id=<?php esc_html_e($_GET['id']); ?>" class="button-secondary" title="Add New Question">Add New Question</a>
			<a href="#" title="Save Order" onclick="saveOrder();" class="button-secondary">Save Order</a>
		</div>
		<?php } ?>		
		<div class="tablenav-pages">
		   <?php echo Wpsqt_Core::getPaginationLinks($currentPage, $numberOfPages); ?>
		</div>		
	</div>

</div>
<?php require_once WPSQT_DIR.'/pages/admin/shared/image.php'; 

function displayTree($array) {
     $newline = "<br>";
     foreach($array as $key => $value) {    //cycle through each item in the array as key => value pairs
         if (is_array($value) || is_object($value)) {        //if the VALUE is an array, then
            //call it out as such, surround with brackets, and recursively call displayTree.
             $value = "Array()" . $newline . "(<ul>" . displayTree($value) . "</ul>)" . $newline;
         }
        //if value isn't an array, it must be a string. output its' key and value.
        $output .= "[$key] => " . $value . $newline;
     }
     return $output;
}
?>
