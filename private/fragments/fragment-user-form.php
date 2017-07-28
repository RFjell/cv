
			<li>
				<div id="asd">
					<div>
						<label for="firstname">First name: </label>
						<input type="text" name="firstname" value="<?=isset($first_name)?$first_name:''?>" required>
					</div>
					<div>
						<label for="lastname">Last name: </label>
						<input type="text" name="lastname" value="<?=isset($last_name)?$last_name:''?>" required>
					</div>
				</div>
			</li>

			<li>
				<label for="phone-number">Phone number: </label>
				<input type="text" name="phone-number" value="<?=isset($phone_number)?$phone_number:''?>" required>
			</li>

			<li>
				<label for="address">Address: </label>
				<input type="text" name="address" value="<?=isset($address)?$address:''?>">
			</li>

			<li>
				<div id="asd">
					<div>
						<label for="zip-code">Zip Code: </label>
						<input type="text" name="zip-code" value="<?=isset($zip_code)?$zip_code:''?>">
					</div>
					<div>
						<label for="city">City: </label>
						<input type="text" name="city" value="<?=isset($city)?$city:''?>">
					</div>
				</div>
			</li>

			<li>
				<label for="linkedin">LinkedIn: </label>
				<input type="text" name="linkedin" value="<?=isset($linkedin)?$linkedin:''?>">
			</li>
