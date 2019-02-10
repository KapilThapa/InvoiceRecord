<template>
	<div id="oldbills">
		<div class="row">
			<div class="col-md-8">
				<div class="form-group">
					<label for="bill_no">Bill No. :</label>
					<input type="number" name="bill_no" min="1" id="bill_no" v-model="bill_no" class="form-control" v-on:keydown.13="fetchBillDetail()">
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12">
				<div class="well">
					<div class="row">
						<div class="col-md-6"><strong>Total Amount :</strong></div>
						<div class="col-md-6 text-right">Rs. {{total}}</div>
					</div>
					<div class="row">
						<div class="col-md-6"><strong>Total Paid :</strong></div>
						<div class="col-md-6 text-right ">Rs. {{total_advance_paid}}</div>
					</div>
					<div class="row">
						<div class="col-md-6"><strong>Total Due :</strong></div>
						<div class="col-md-6 text-right">Rs. {{due}}</div>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12">
				<div class="form-group">
					<label for="pay_amount">Pay Amount :</label>
					<input type="text" class="form-control" id="pay_amount" v-model="pay_amount" :disabled="disable_pay">
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12">
				<div class="form-group">
					<label class="checkbox-inline"><input type="checkbox" v-model="delivered">Delivered</label>
				</div>
			</div>
		</div>
		<div class="row" v-if="pay_amount < due">
			<div class="col-md-12">
				<div class="form-group">
					<label for="discount_note">Discount Note :</label>
					<textarea name="discount_note" id="discount_note" v-model="discount_note" rows="2" class="form-control"></textarea>
				</div>
			</div>
		</div>
		<hr>
		<div class="row">
			<div class="col-sm-12">
				<button class="btn btn-default" @click="updateBillDetail()">Save</button>
				<button class="btn btn-danger" @click="cancelOrder()">Cancel Order</button>
			</div>
		</div>
	</div>
</template>

<script>
	import { required, minValue, between, requiredIf } from 'vuelidate/lib/validators'
	function lessthan(){
		return parseInt(this.due) >= parseInt(this.pay_amount);
	}
	export default {
		data(){
			return{
				bill_no:'',
				total:0,
				total_advance_paid:0,
				due:0,
				pay_amount:'',
				delivered:true,
				disable_pay:false,
				discount_note:'',
			}
		},
		validations:{
			bill_no:{required},
			pay_amount:{required,lessthan},
			discount_note: {
				required: requiredIf(function () {
               return this.pay_amount < this.due
            })
			}
		},
		methods:{
			fetchBillDetail(){
				let that = this;
				if (that.bill_no > 0) {
					axios.get('api/invoice/'+that.bill_no+'/edit')
					.then((response)=>{
						that.total = response.data[0].bill_total;
						that.total_advance_paid = response.data[0].total_paid;
						that.due = response.data[0].due;
						if (that.due == 0) {
							that.pay_amount=0;
							that.disable_pay = true;
						}else{
							$("#pay_amount").focus();
						}
					})
					.catch(function (error) {
                  if (error.response.status == 404) {
                     swal(error.response.status.toString(),error.response.data.msg, {
                        icon: "error",
                     });
                     that.total = 0;
							that.total_advance_paid = 0;
							that.due = 0;
							that.pay_amount = 0;
							that.discount_note = '';
							that.disable_pay = true;
                  }
               });
				}
			},
			cancelOrder(){

			},
			updateBillDetail(){
				let that = this;
				that.$v.$touch();
				if (that.total > 0) {
					console.log('total');
					if (that.$v.$invalid) {
	               swal("Oops!",'There are incomplete required fields. Please fill them to continue.',{
	                  icon:"error"
	               });
	            }else{
	            	console.log('here');
	            	axios.put('api/invoice/'+that.bill_no,{pay_amount:that.pay_amount,discount_note:that.discount_note})
	            	.then((response)=>{

	            	});
	            }
				}
			}
		}
	}
</script>