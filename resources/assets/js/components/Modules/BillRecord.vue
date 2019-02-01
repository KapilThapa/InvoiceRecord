<template>
	<div id="newbills">
		<div class="row">
			<div class="col-md-8">
				<div class="form-group">
					<label for="bill_no">Bill No. :</label>
					<div class="input-group">
						<input type="number" name="bill_no" min="0" id="bill_no" v-model:value="bill.bill_no" class="form-control" :disabled="bill_no_lock" v-on:keydown.13="focusOnEnter('#customer_name')" @blur="focusOnEnter('#customer_name')">
						<div class="input-group-append">
							<button class="btn btn-default" @click="togglelock()"><i :class="bill_no_lock ? 'fa fa-unlock' : 'fa fa-lock'"></i></button>
						</div>
						<span v-if="bill_exist">bill already exist!</span>
					</div>
				</div>
			</div>
		</div>
		<div class="form-group">
			<label for="customer_name">Customer's Name :</label>
			<input type="text" id="customer_name" v-model:value="bill.customer_name" placeholder="Enter name if provided" class="form-control text-capitalize" v-on:keydown.13="focusOnEnter('#contact')">
		</div>
		<div class="form-group">
			<label for="contact">Contact :</label>
			<input type="number" id="contact" v-model:value="bill.contact" placeholder="Enter contact no. if provided" class="form-control" v-on:keydown.13="focusOnEnter('#total')">
		</div>
		<div class="row">
			<div class="col-md-6">
				<div class="form-group">
				<label for="total">Bill Total :</label>
				<input type="number" name="total" id="total" min="0" v-model:value="bill.total" class="form-control" v-on:keydown.13="focusOnEnter('#advance')">
			</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					<label for="advance">Advance :</label>
					<input type="number" id="advance" min="0" v-model:value="bill.advance" class="form-control" v-on:keydown.13="saveBillDetail()">
				</div>
			</div>
		</div>
		<div class="form-group">
			<label for="due">Due :</label>
			<input type="number" id="due" min="0" v-model:value="bill.due" class="form-control" disabled>
		</div>
		<hr>
		<div class="row">
			<div class="col-sm-offset-3 col-sm-9">
				<button class="btn btn-default" @click="saveBillDetail()" type="submit">Save</button>
			</div>
		</div>
	</div>
</template>

<script>
	import { required, minValue, between } from 'vuelidate/lib/validators'
	export default {
		data(){
			return{
				bill:{
					bill_no:'',
					customer_name:'',
					contact:'',
					total:'',
					advance:'',
					due:''
				},
				bill_no_lock: false,
				bill_exist: false,
			}
		},
		validations: {
			bill:{
				bill_no:{required},
				total:{required},
				advance:{required},
				due:{required}
			}
		},
		methods:{
			togglelock(){
				let that = this;
				if(that.bill_no_lock){
					that.bill_no_lock= false;
				}else{
					that.bill_no_lock= true;
				}
			},
			focusOnEnter(input){
				let that = this;
				if(input == "#customer_name"){
					$.ajax({
						url: '/api/checkbill/'+that.bill.bill_no,
						type: 'GET',
						headers: {
							'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
						},
					})
					.done(function(response) {
						if(response==0){
							that.bill_exist=true;
						}else{
							that.bill_exist=false;
							$(input).focus();
						}
					})
					.fail(function(response) {
					})
					.always(function(response) {
					});
				}
				else if(input == "#total"){
					// check length of name
					$(input).focus();
				}
				else{
					$(input).focus();
				}
			},
			saveBillDetail(){

			}
		}
	}
</script>