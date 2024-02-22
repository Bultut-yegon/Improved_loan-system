let form = document.getElementById('form');
let loan_name = document.getElementById('loan-name');
let loan_amount = document.getElementById('loan-amount');
let interest_rate = document.getElementById('interest-rate');
let term = document.getElementById('term');
let date = document.getElementById('start-date');

const validation = new JustValidate('#form');

validation
	.addField(loan_name, [
		{
			rule: "required"
		}
	])
	.addField(date, [
		{
			rule: "required"
		}
	])
	.addField(loan_amount, [
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
	.addField(interest_rate, [
		{
			rule: "required"
		},
		{
			validator: (value) => {
				return (value > 0)
			},
			errorMessage: "interest rate should be greater than zero"
		},
		{
			validator: (value) => {
				return (value < 40)
			},
			errorMessage: "interest rate should be less than 40%"
		}
	])
	.addField(term, [
		{
			rule: "required"
		},
		{
			validator: (value) => {
				return (value > 0)
			},
			errorMessage: "Term should be greater than zero"
		}
	])
	.onSuccess((event) => {
	form.submit();
	});