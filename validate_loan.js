let form = document.getElementById('form');
let payment_amount = document.getElementById('payment-amount');
let date = document.getElementById('payment-date');

const validation = new JustValidate('#form');


validation
	.addField(date, [
		{
			rule: "required"
		}
	])
	.addField(payment_amount, [
		{
			rule: "required"
		},
		{
			validator: (value) => {
				return (value > 0)
			},
			errorMessage: "amount should be greater than zero"
		}
	])
	.onSuccess((event) => {
	form.submit();
	});