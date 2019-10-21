<?php session_start();

  $root = ($_SERVER['SERVER_NAME'] == 'ttal.loc') ? '/' : '';

  include_once $_SERVER["DOCUMENT_ROOT"].$root.'classes/general.php';
  
	$translation = getTranslation($_SESSION['lang']);

	if(isset($_POST["keyword"])){
		if($_POST["keyword"] != ''){
			$repositories = getSearchResultByID($_SESSION['lang'],$_POST["keyword"]);

			if(count($repositories)>0){
				echo '
				<div class="search_title">
					'.$translation[52].'
				</div>
				';

				foreach ($repositories as $repository) {
					$repository_details = getRepositoryDetailsById($repository);
					$repository_relations = getRepositoryRelations($repository_details["id"]);
				
					if($repository_details["art"] == 'step_2_safety_datasheet'){
						$art = 'SDB';
						$art_explanation = $translation[103];
					}
					elseif($repository_details["art"] == 'step_2_product_datasheet'){
						$art = 'PDB';
						$art_explanation = $translation[104];
					}
					else{
						$art = 'GDB';
					}

					$prepare_name = $art.'_'.$repository_details["name"];
					$pdf_name = makeURL($prepare_name);

					$repository_date_exp = explode(" ",$repository_details["modification_date"]);
					$repository_date_exp_deep = explode("-",$repository_date_exp[0]);
					$repository_date = $repository_date_exp_deep[2].".".$repository_date_exp_deep[1].".".$repository_date_exp_deep[0];

					if($repository_relations){
						$relations = 'has_relations';
					}
					else{
						$relations = '';
					}

					echo '<div class="autocomplete-suggestion">';
						echo '<div class="repository_name '.$relations.'">'.$repository_details["name"].'<span class="repository_art_mobile">'.$art.'</span><span class="repository_date_mobile">'.$translation[11].'<br>'.$repository_date.'</span></div>';
						if(!$repository_relations){
							echo '<div class="repository_details">';
								
								echo '<span class="repository_art">'.$art.'<span class="repository_art_explanation">'.$art_explanation.'</span></span>';
								echo '<span class="repository_date">'.$translation[11].'<br>'.$repository_date.'</span>';

								if($repository_details["pdf_link"] != ''){
									echo '<span class="repository_button"><span class="open_in_pdf_viewer" data-pdf-name="'.$pdf_name.'" data-search_repository_id="'.$repository_details["id"].'"><i class="pe-7s-expand1"></i>'.$translation[12].'</span></span>';
									echo '<span class="repository_button"><span class="direct_download" data-pdf-name="'.$pdf_name.'" data-search_repository_id="'.$repository_details["id"].'"><i class="pe-7s-cloud-download"></i>'.$translation[13].'</span></span>';

									if(isset($_COOKIE['dm_repos'])) {
										$dm_repos_content = json_decode($_COOKIE['dm_repos'], true);
										if(in_array($repository_details["id"], $dm_repos_content)) {
											echo '
											<span class="repository_button">
												<span class="add_to_download_manager make_invisible" data-search_repository_id="'.$repository_details["id"].'"><i class="pe-7s-plus"></i>'.$translation[14].'<br>'.$translation[59].'
												</span>
												<span class="add_to_download_manager added_to_download_manager" data-search_repository_id="'.$repository_details["id"].'"><i class="pe-7s-check"></i>'.$translation[78].'<br>'.$translation[77].'</span>
											</span>';
										}
										else{
											echo '
											<span class="repository_button">
												<span class="add_to_download_manager" data-search_repository_id="'.$repository_details["id"].'"><i class="pe-7s-plus"></i>'.$translation[14].'<br>'.$translation[59].'</span>
												<span class="add_to_download_manager added_to_download_manager make_invisible" data-search_repository_id="'.$repository_details["id"].'"><i class="pe-7s-check"></i>'.$translation[78].'<br>'.$translation[77].'</span>
											</span>';

										}
									}
									else{
										echo '
										<span class="repository_button">
											<span class="add_to_download_manager added_to_download_manager make_invisible" data-search_repository_id="'.$repository_details["id"].'"><i class="pe-7s-check"></i>'.$translation[78].'<br>'.$translation[77].'</span>
											<span class="add_to_download_manager" data-search_repository_id="'.$repository_details["id"].'"><i class="pe-7s-plus"></i>'.$translation[14].'<br>'.$translation[59].'</span>
										</span>';
									}
								}
							
							echo '</div>';
						}
						else{
							echo '<div class="repository_details">';
							echo '</div>';
						}
						

						if($repository_relations){
							echo '<div class="repository_related_container">';

								//parent item
								echo '<div class="autocomplete-suggestion">';
									echo '<div class="repository_name">'.$repository_details["name"].'<span class="repository_art_mobile">'.$art.'</span><span class="repository_date_mobile">'.$translation[11].'<br>'.$repository_date.'</span></div>';
									echo '<div class="repository_details">';
										
										echo '<span class="repository_art">'.$art.'<span class="repository_art_explanation">'.$art_explanation.'</span></span>';
										echo '<span class="repository_date">'.$translation[11].'<br>'.$repository_date.'</span>';

										if($repository_details["pdf_link"] != ''){
											echo '<span class="repository_button"><span class="open_in_pdf_viewer" data-pdf-name="'.$pdf_name.'" data-search_repository_id="'.$repository_details["id"].'"><i class="pe-7s-expand1"></i>'.$translation[12].'</span></span>';
											echo '<span class="repository_button"><span class="direct_download" data-pdf-name="'.$pdf_name.'" data-search_repository_id="'.$repository_details["id"].'"><i class="pe-7s-cloud-download"></i>'.$translation[13].'</span></span>';



											if(isset($_COOKIE['dm_repos'])) {
												$dm_repos_content = json_decode($_COOKIE['dm_repos'], true);
												if(in_array($repository_details["id"], $dm_repos_content)) {
													echo '
													<span class="repository_button">
														<span class="add_to_download_manager make_invisible" data-search_repository_id="'.$repository_details["id"].'"><i class="pe-7s-plus"></i>'.$translation[14].'<br>'.$translation[59].'
														</span>
														<span class="add_to_download_manager added_to_download_manager" data-search_repository_id="'.$repository_details["id"].'"><i class="pe-7s-check"></i>'.$translation[78].'<br>'.$translation[77].'</span>
													</span>';
												}
												else{
													echo '
													<span class="repository_button">
														<span class="add_to_download_manager" data-search_repository_id="'.$repository_details["id"].'"><i class="pe-7s-plus"></i>'.$translation[14].'<br>'.$translation[59].'</span>
														<span class="add_to_download_manager added_to_download_manager make_invisible" data-search_repository_id="'.$repository_details["id"].'"><i class="pe-7s-check"></i>'.$translation[78].'<br>'.$translation[77].'</span>
													</span>';

												}
											}
											else{
												echo '
												<span class="repository_button">
													<span class="add_to_download_manager added_to_download_manager make_invisible" data-search_repository_id="'.$repository_details["id"].'"><i class="pe-7s-check"></i>'.$translation[78].'<br>'.$translation[77].'</span>
													<span class="add_to_download_manager" data-search_repository_id="'.$repository_details["id"].'"><i class="pe-7s-plus"></i>'.$translation[14].'<br>'.$translation[59].'</span>
												</span>';
											}

										}
								
									echo '</div>';
									
								echo '</div>';


								//related items
								$repository_relations_array = explode(", ",$repository_relations);
								foreach ($repository_relations_array as $repository_relation) {
									$repository_relation_details = getRepositoryDetails($repository_relation);					

									if($repository_relation_details["art"] == 'step_2_safety_datasheet'){
										$art = 'SDB';
										$art_explanation = $translation[103];
									}
									elseif($repository_relation_details["art"] == 'step_2_product_datasheet'){
										$art = 'PDB';
										$art_explanation = $translation[104];
									}
									else{
										$art = 'GDB';
									}

									$repository_relation_date_exp = explode(" ",$repository_relation_details["modification_date"]);
									$repository_relation_date_exp_deep = explode("-",$repository_relation_date_exp[0]);
									$repository_relation_date = $repository_relation_date_exp_deep[2].".".$repository_relation_date_exp_deep[1].".".$repository_relation_date_exp_deep[0];


									$prepare_name = $art.'_'.$repository_relation;
									$pdf_name = makeURL($prepare_name);


									echo '<div class="autocomplete-suggestion">';
										echo '<div class="repository_name">'.$repository_relation.'<span class="repository_art_mobile">'.$art.'</span><span class="repository_date_mobile">'.$translation[11].'<br>'.$repository_relation_date.'</span></div>';
										echo '<div class="repository_details">';
											
											echo '<span class="repository_art">'.$art.'<span class="repository_art_explanation">'.$art_explanation.'</span></span>';
											echo '<span class="repository_date">'.$translation[11].'<br>'.$repository_relation_date.'</span>';

											if($repository_relation_details["pdf_link"] != ''){
												echo '<span class="repository_button"><span class="open_in_pdf_viewer" data-pdf-name="'.$pdf_name.'" data-search_repository_id="'.$repository_relation_details["id"].'"><i class="pe-7s-expand1"></i>'.$translation[12].'</span></span>';
												echo '<span class="repository_button"><span class="direct_download" data-pdf-name="'.$pdf_name.'" data-search_repository_id="'.$repository_relation_details["id"].'"><i class="pe-7s-cloud-download"></i>'.$translation[13].'</span></span>';



												if(isset($_COOKIE['dm_repos'])) {
													$dm_repos_content = json_decode($_COOKIE['dm_repos'], true);
													if(in_array($repository_relation_details["id"], $dm_repos_content)) {
														echo '
														<span class="repository_button">
															<span class="add_to_download_manager make_invisible" data-search_repository_id="'.$repository_relation_details["id"].'"><i class="pe-7s-plus"></i>'.$translation[14].'<br>'.$translation[59].'
															</span>
															<span class="add_to_download_manager added_to_download_manager" data-search_repository_id="'.$repository_relation_details["id"].'"><i class="pe-7s-check"></i>'.$translation[78].'<br>'.$translation[77].'</span>
														</span>';
													}
													else{
														echo '
														<span class="repository_button">
															<span class="add_to_download_manager" data-search_repository_id="'.$repository_relation_details["id"].'"><i class="pe-7s-plus"></i>'.$translation[14].'<br>'.$translation[59].'</span>
															<span class="add_to_download_manager added_to_download_manager make_invisible" data-search_repository_id="'.$repository_relation_details["id"].'"><i class="pe-7s-check"></i>'.$translation[78].'<br>'.$translation[77].'</span>
														</span>';

													}
												}
												else{
													echo '
													<span class="repository_button">
														<span class="add_to_download_manager added_to_download_manager make_invisible" data-search_repository_id="'.$repository_relation_details["id"].'"><i class="pe-7s-check"></i>'.$translation[78].'<br>'.$translation[77].'</span>
														<span class="add_to_download_manager" data-search_repository_id="'.$repository_relation_details["id"].'"><i class="pe-7s-plus"></i>'.$translation[14].'<br>'.$translation[59].'</span>
													</span>';
												}

											}
									
										echo '</div>';
										
									echo '</div>';
								}
							echo '</div>';
						}

					echo '</div>';
				}
			}
			
//			$repositories_pdf = getSearchResultFromPDFContent($_SESSION['lang'],$_POST["keyword"]);
//
//			if(count($repositories_pdf)>0){
//				echo '
//				<div class="search_title">
//					'.$translation[53].'
//				</div>
//				';
//
//
//				foreach ($repositories_pdf as $repository) {
//					$repository_details = getRepositoryDetailsById($repository);
//					$repository_relations = getRepositoryRelations($repository_details["id"]);
//
//					if($repository_details["art"] == 'step_2_safety_datasheet'){
//						$art = 'SDB';
//					}
//					elseif($repository_details["art"] == 'step_2_product_datasheet'){
//						$art = 'PDB';
//					}
//					else{
//						$art = 'GDB';
//					}
//
//					$repository_date_exp = explode(" ",$repository_details["modification_date"]);
//					$repository_date_exp_deep = explode("-",$repository_date_exp[0]);
//					$repository_date = $repository_date_exp_deep[2].".".$repository_date_exp_deep[1].".".$repository_date_exp_deep[0];
//
//					$prepare_name = $art.'_'.$repository_details["name"];
//					$pdf_name = makeURL($prepare_name);
//
//
//					echo '<div class="autocomplete-suggestion">';
//						echo '<div class="repository_name">'.$repository_details["name"].'</div>';
//						echo '<div class="repository_details">';
//
//							if($repository_relations){
//								echo '<span class="repository_relations_button relation_closed"><i class="pe-7s-angle-right"></i>'.$translation[10].'</span>';
//							}
//
//							echo '<span class="repository_art">'.$art.'</span>';
//							echo '<span class="repository_date">'.$translation[11].'<br>'.$repository_date.'</span>';
//
//							if($repository_details["pdf_link"] != ''){
//								echo '<span class="repository_button"><span class="open_in_pdf_viewer" data-pdf-name="'.$pdf_name.'" data-search_repository_id="'.$repository_details["id"].'"><i class="pe-7s-expand1"></i>'.$translation[12].'</span></span>';
//								echo '<span class="repository_button"><span class="direct_download" data-pdf-name="'.$pdf_name.'" data-search_repository_id="'.$repository_details["id"].'"><i class="pe-7s-cloud-download"></i>'.$translation[13].'</span></span>';
//
//
//								if(isset($_COOKIE['dm_repos'])) {
//									$dm_repos_content = json_decode($_COOKIE['dm_repos'], true);
//									if(in_array($repository_details["id"], $dm_repos_content)) {
//										echo '
//										<span class="repository_button">
//											<span class="add_to_download_manager make_invisible" data-search_repository_id="'.$repository_details["id"].'"><i class="pe-7s-plus"></i>'.$translation[14].'<br>'.$translation[59].'
//											</span>
//											<span class="add_to_download_manager added_to_download_manager" data-search_repository_id="'.$repository_details["id"].'"><i class="pe-7s-check"></i>'.$translation[78].'<br>'.$translation[77].'</span>
//										</span>';
//									}
//									else{
//										echo '
//										<span class="repository_button">
//											<span class="add_to_download_manager" data-search_repository_id="'.$repository_details["id"].'"><i class="pe-7s-plus"></i>'.$translation[14].'<br>'.$translation[59].'</span>
//											<span class="add_to_download_manager added_to_download_manager make_invisible" data-search_repository_id="'.$repository_details["id"].'"><i class="pe-7s-check"></i>'.$translation[78].'<br>'.$translation[77].'</span>
//										</span>';
//
//									}
//								}
//								else{
//									echo '
//									<span class="repository_button">
//										<span class="add_to_download_manager added_to_download_manager make_invisible" data-search_repository_id="'.$repository_details["id"].'"><i class="pe-7s-check"></i>'.$translation[78].'<br>'.$translation[77].'</span>
//										<span class="add_to_download_manager" data-search_repository_id="'.$repository_details["id"].'"><i class="pe-7s-plus"></i>'.$translation[14].'<br>'.$translation[59].'</span>
//									</span>';
//								}
//
//							}
//
//						echo '</div>';
//
//						if($repository_relations){
//							echo '<div class="repository_related_container">';
//								$repository_relations_array = explode(", ",$repository_relations);
//								foreach ($repository_relations_array as $repository_relation) {
//									$repository_relation_details = getRepositoryDetails($repository_relation);
//
//									if($repository_relation_details["art"] == 'step_2_safety_datasheet'){
//										$art = 'SDB';
//									}
//									elseif($repository_relation_details["art"] == 'step_2_product_datasheet'){
//										$art = 'PDB';
//									}
//									else{
//										$art = 'GDB';
//									}
//
//									$repository_relation_date_exp = explode(" ",$repository_relation_details["modification_date"]);
//									$repository_relation_date_exp_deep = explode("-",$repository_relation_date_exp[0]);
//									$repository_relation_date = $repository_relation_date_exp_deep[2].".".$repository_relation_date_exp_deep[1].".".$repository_relation_date_exp_deep[0];
//
//
//									$prepare_name = $art.'_'.$repository_relation;
//									$pdf_name = makeURL($prepare_name);
//
//
//									echo '<div class="autocomplete-suggestion">';
//										echo '<div class="repository_name">'.$repository_relation.'</div>';
//										echo '<div class="repository_details">';
//
//											echo '<span class="repository_art">'.$art.'</span>';
//											echo '<span class="repository_date">'.$translation[11].'<br>'.$repository_relation_date.'</span>';
//
//											if($repository_relation_details["pdf_link"] != ''){
//												echo '<span class="repository_button"><span class="open_in_pdf_viewer" data-pdf-name="'.$pdf_name.'" data-search_repository_id="'.$repository_relation_details["id"].'"><i class="pe-7s-expand1"></i>'.$translation[12].'</span></span>';
//												echo '<span class="repository_button"><span class="direct_download" data-pdf-name="'.$pdf_name.'" data-search_repository_id="'.$repository_relation_details["id"].'"><i class="pe-7s-cloud-download"></i>'.$translation[13].'</span></span>';
//
//												if(isset($_COOKIE['dm_repos'])) {
//													$dm_repos_content = json_decode($_COOKIE['dm_repos'], true);
//													if(in_array($repository_relation_details["id"], $dm_repos_content)) {
//														echo '
//														<span class="repository_button">
//															<span class="add_to_download_manager make_invisible" data-search_repository_id="'.$repository_relation_details["id"].'"><i class="pe-7s-plus"></i>'.$translation[14].'<br>'.$translation[59].'
//															</span>
//															<span class="add_to_download_manager added_to_download_manager" data-search_repository_id="'.$repository_relation_details["id"].'"><i class="pe-7s-check"></i>'.$translation[78].'<br>'.$translation[77].'</span>
//														</span>';
//													}
//													else{
//														echo '
//														<span class="repository_button">
//															<span class="add_to_download_manager" data-search_repository_id="'.$repository_relation_details["id"].'"><i class="pe-7s-plus"></i>'.$translation[14].'<br>'.$translation[59].'</span>
//															<span class="add_to_download_manager added_to_download_manager make_invisible" data-search_repository_id="'.$repository_relation_details["id"].'"><i class="pe-7s-check"></i>'.$translation[78].'<br>'.$translation[77].'</span>
//														</span>';
//
//													}
//												}
//												else{
//													echo '
//													<span class="repository_button">
//														<span class="add_to_download_manager added_to_download_manager make_invisible" data-search_repository_id="'.$repository_relation_details["id"].'"><i class="pe-7s-check"></i>'.$translation[78].'<br>'.$translation[77].'</span>
//														<span class="add_to_download_manager" data-search_repository_id="'.$repository_relation_details["id"].'"><i class="pe-7s-plus"></i>'.$translation[14].'<br>'.$translation[59].'</span>
//													</span>';
//												}
//
//											}
//
//										echo '</div>';
//
//									echo '</div>';
//								}
//							echo '</div>';
//						}
//
//					echo '</div>';
//				}
//			}
		}
	}


?>