<?php session_start();
	if (isset($_SESSION['admin_logged_in'])) {
		//logged

		include_once 'classes/general.php';

		$user_details = getUserDetails($_GET['user']);
		$google_login_status = checkIfUserAllowedForGoogleLogin($user_details["email"]);
		if($google_login_status == 'allowed'){
			$google_login_allowed = 'selected';
			$google_login_not_allowed = '';
		}
		else{
			$google_login_allowed = '';
			$google_login_not_allowed = 'selected';
		}

		$regular_login_status = checkIfUserAllowedForRegularLogin($user_details["email"]);
		if($regular_login_status == 'allowed'){
			$regular_login_allowed = 'selected';
			$regular_login_not_allowed = '';
		}
		else{
			$regular_login_allowed = '';
			$regular_login_not_allowed = 'selected';
		}

		if($user_details != 'user not found'){
			?>
			<div class="main_table_container">
				
				<div class="account_edit_container">
					<div class="account_edit_container_title">
						Anmeldung via google sign-on
					</div>
					<div class="account_edit_container_description">
						Für die Anmeldung mit google sign-on ist eine gmail Adresse erforderlich. Bitte tragen oder korrigieren Sie Ihre E-Mail Adresse nachfolgend ein. Sofern Sie google sign-on erstmalig einrichten, müssen Sie das google sign-on beantragen.
					</div>

					<div class="account_edit_content">
						<div class="form-line">
							<label for="login_with_google_email_address">Ihre gmail Adresse:</label>
							<input type="text" name="login_with_google_email_address" id="login_with_google_email_address" value="<?php echo $user_details["email"]; ?>">
							<div class="form_info"><i class="pe-7s-check"></i>E-Mail Adresse ist verifiziert & zur Anmeldung verfügbar</div>
						</div>
						<input type="text" hidden name="login_with_google_email_address_old" id="login_with_google_email_address_old" value="<?php echo $user_details["email"]; ?>">
						<input type="text" hidden name="login_with_google_user_id" id="login_with_google_user_id" value="<?php echo $user_details["id"]; ?>">

						<div class="login_type_status">
							<div class="custom-select">
								<select name="login_with_google_login_type_status" id="login_with_google_login_type_status">
									<option value="0">SELECT:</option>
									<option value="1" <?php echo $google_login_allowed; ?>>AKTIV</option>
									<option value="2" <?php echo $google_login_not_allowed; ?>>INAKTIV</option>
								</select>
							</div>
						</div>

						<button type="submit" class="form_button save_login_with_google_email_address">Bestätigen</button>
					</div>

				</div>

				<div class="account_edit_container">
					<div class="account_edit_container_title">
						Anmeldung via Benutzername & Passwort
					</div>
					<div class="account_edit_container_description">
						Ihr Benutzername ist vorgegeben und setzte sich aus Ihrem Vor- und Nachnamen zusammen. Tragen Sie nachfolgend Ihr Passwort ein und bestätigen Sie Ihre Angaben.
					</div>

					<div class="account_edit_content">
						<div class="form_line_row">
							<div class="form-line">
								<label for="login_regular_firstname">Vorname:</label>
								<input type="text" name="login_regular_firstname" id="login_regular_firstname" value="<?php echo $user_details["first_name"]; ?>">
							</div>
							<div class="form-line">
								<label for="login_regular_lastname">Nachname:</label>
								<input type="text" name="login_regular_lastname" id="login_regular_lastname" value="<?php echo $user_details["last_name"]; ?>">
							</div>
						</div>
						<div class="form_line_row">
							<div class="form-line">
								<label for="login_regular_password_1">Passwort:</label>
								<input type="password" name="login_regular_password_1" id="login_regular_password_1" value="">
							</div>
							<div class="form-line">
								<label for="login_regular_password_2">Passwort wiederholen:</label>
								<input type="password" name="login_regular_password_2" id="login_regular_password_2" value="">
							</div>
						</div>

						<div class="login_type_status">
							<div class="custom-select">
								<select name="regular_login_type_status" id="regular_login_type_status">
									<option value="0">SELECT:</option>
									<option value="1" <?php echo $regular_login_allowed; ?>>AKTIV</option>
									<option value="2" <?php echo $regular_login_not_allowed; ?>>INAKTIV</option>
								</select>
							</div>
						</div>

						<input type="text" hidden name="login_regular_user_id" id="login_regular_user_id" value="<?php echo $user_details["id"]; ?>">
						<button type="submit" class="form_button save_login_regular">Bestätigen</button>
					</div>
				</div>

			</div>

			<script>
				var x, i, j, selElmnt, a, b, c;
				/*look for any elements with the class "custom-select":*/
				x = document.getElementsByClassName("custom-select");
				for (i = 0; i < x.length; i++) {
				  selElmnt = x[i].getElementsByTagName("select")[0];
				  /*for each element, create a new DIV that will act as the selected item:*/
				  a = document.createElement("DIV");
				  a.setAttribute("class", "select-selected");
				  a.innerHTML = selElmnt.options[selElmnt.selectedIndex].innerHTML;
				  x[i].appendChild(a);
				  /*for each element, create a new DIV that will contain the option list:*/
				  b = document.createElement("DIV");
				  b.setAttribute("class", "select-items select-hide");
				  for (j = 1; j < selElmnt.length; j++) {
				    /*for each option in the original select element,
				    create a new DIV that will act as an option item:*/
				    c = document.createElement("DIV");
				    c.innerHTML = selElmnt.options[j].innerHTML;
				    c.addEventListener("click", function(e) {
				        /*when an item is clicked, update the original select box,
				        and the selected item:*/
				        var y, i, k, s, h;
				        s = this.parentNode.parentNode.getElementsByTagName("select")[0];
				        h = this.parentNode.previousSibling;
				        for (i = 0; i < s.length; i++) {
				          if (s.options[i].innerHTML == this.innerHTML) {
				            s.selectedIndex = i;
				            h.innerHTML = this.innerHTML;
				            y = this.parentNode.getElementsByClassName("same-as-selected");
				            for (k = 0; k < y.length; k++) {
				              y[k].removeAttribute("class");
				            }
				            this.setAttribute("class", "same-as-selected");
				            break;
				          }
				        }
				        h.click();
				    });
				    b.appendChild(c);
				  }
				  x[i].appendChild(b);
				  a.addEventListener("click", function(e) {
				      /*when the select box is clicked, close any other select boxes,
				      and open/close the current select box:*/
				      e.stopPropagation();
				      closeAllSelect(this);
				      this.nextSibling.classList.toggle("select-hide");
				      this.classList.toggle("select-arrow-active");
				    });
				}
				function closeAllSelect(elmnt) {
				  /*a function that will close all select boxes in the document,
				  except the current select box:*/
				  var x, y, i, arrNo = [];
				  x = document.getElementsByClassName("select-items");
				  y = document.getElementsByClassName("select-selected");
				  for (i = 0; i < y.length; i++) {
				    if (elmnt == y[i]) {
				      arrNo.push(i)
				    } else {
				      y[i].classList.remove("select-arrow-active");
				    }
				  }
				  for (i = 0; i < x.length; i++) {
				    if (arrNo.indexOf(i)) {
				      x[i].classList.add("select-hide");
				    }
				  }
				}
				/*if the user clicks anywhere outside the select box,
				then close all select boxes:*/
				document.addEventListener("click", closeAllSelect);
			</script>

			<?php
		}
		else{
			?>
				<script type="text/javascript">
					window.location.href = "account.php";
				</script>
			<?php
		}
	}
?>