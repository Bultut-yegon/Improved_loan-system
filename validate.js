let form = document.getElementById('form');
let name = document.getElementById('name');
let email = document.getElementById('email');
let password = document.getElementById('password');
let password_confirm = document.getElementById('password-confirm');

const validation = new JustValidate('#form');

validation
	.addField(name, [
		{
			rule: "required"
		}
	])
	.addField(email, [
		{
			rule: "required"
		},
		{
			rule: "email"
		},
		{
			validator: (value) => () => {
				return fetch("validate-email.php?email=" + encodeURIComponent(value))
				.then(function(response){
					 return response.json();
				})
				.then(function(json){
					return json.available;
				});
			},
			errorMessage: "email already taken"
		}
	])
	.addField(password, [
		{
			rule: "required"
		},
		{
			rule: "password"
		}

	])
	.addField(password_confirm, [
		{
			validator: (value, fields) => {
				return value === password.value;
			},
			errorMessage: "Passwords do not match"
		}
	])
	.onSuccess((event) => {
		form.submit();
	});