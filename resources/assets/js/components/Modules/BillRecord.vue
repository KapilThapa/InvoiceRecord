<template>
	<div id="newbills">
		<div class="row">
			<div class="col-md-8">
				<div class="form-group" :class="{ 'has-error': $v.bill.bill_no.$error || bill_exist }">
					<label for="bill_no">Bill No. :</label>
					<div class="input-group">
						<input type="number" name="bill_no" min="1" id="bill_no" v-model:value="bill.bill_no" class="form-control" :disabled="bill_no_lock" v-on:keydown.13="focusOnEnter('#delivery_date')">
						<div class="input-group-btn">
							<button class="btn btn-default" @click="togglelock()"><i :class="bill_no_lock ? 'fa fa-unlock' : 'fa fa-lock'"></i></button>
						</div>
					</div>
					<span v-if="bill_exist">bill already exist!</span>
				</div>
			</div>
		</div>
		<div class="form-group">
			<label for="delivery_date">Delivery Date</label>
			<date-picker lang="en" :not-before="new Date()" type="date" v-model="bill.delivery_date" :date-format="'YYYY/MM/DD'" value-type='format' :format="'YYYY/MM/DD'" width="100%" :input-class="'form-control'" @clear="resetDate()" :input-attr="{id:'delivery_date'}"></date-picker>
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
				<div class="form-group" :class="{ 'has-error': $v.bill.total.$error }">
				<label for="total">Bill Total :</label>
				<input type="number" name="total" id="total" min="0" v-model:value="bill.total" class="form-control" v-on:keydown.13="focusOnEnter('#advance')">
			</div>
			</div>
			<div class="col-md-6">
				<div class="form-group" :class="{ 'has-error': $v.bill.advance.$error }">
					<label for="advance">Advance :</label>
					<input type="number" id="advance" min="0" v-model:value="bill.advance" class="form-control" v-on:keydown.13="saveBillDetail()">
				</div>
			</div>
		</div>
		<div class="form-group">
			<label for="due">Due :</label>
			<input type="number" id="due" min="0" v-model:value="due" class="form-control" disabled>
		</div>
		<div class="form-group">
			<label for="note">Note :</label>
			<textarea name="note" id="note" rows="2" v-model:value="bill.note" class="form-control"></textarea>
		</div>
		<hr>
		<div class="row">
			<div class="col-sm-12">
				<button class="btn btn-default" @click="saveBillDetail()">Save</button>
			</div>
		</div>
	</div>
</template>

<script>
	import { required, minValue, between } from 'vuelidate/lib/validators'
	import DatePicker from 'vue2-datepicker'
	function lessthan(){
		return parseInt(this.bill.total) >= parseInt(this.bill.advance);
	}
	export default {
		data(){
			return{
				bill:{
					bill_no:'',
					delivery_date:'',
					customer_name:'',
					contact:'',
					total:'',
					advance:'',
					note:''
				},
				bill_no_lock: false,
				bill_exist: false,
			}
		},
		components:{
         DatePicker
      },
		validations: {
			bill:{
				bill_no:{required},
				delivery_date:{required},
				total:{required},
				advance:{lessthan}
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
				if(input == "#delivery_date"){
					axios.get('/api/checkbill/'+that.bill.bill_no)
					.then(function(response) {
						if(response.data==0){
							that.bill_exist=true;
						}else{
							that.bill_exist=false;
							$(input).focus();
						}
					})
					.catch(function(response) {
					})
				}
				else if(input == "#total"){
					// check length of name
					$(input).focus();
				}
				else{
					$(input).focus();
				}
			},
			resetDate(){
				this.bill.delivery_date = new Date();
			},
			saveBillDetail(){
				let that = this;
            that.$v.$touch();
            if (that.bill_exist) {
            	swal("Oops!",'This Bill has already been recorded.',{
                  icon:"error"
               });
            }else{
	            if (that.$v.$invalid) {
	               swal("Oops!",'There are incomplete required fields. Please fill them to continue.',{
	                  icon:"error"
	               });
	            } else {
	            	axios.post('/api/invoice', that.bill)
	               .then(function (response) {
	                  swal(response.data.msg, {
	                     icon: "success",
	                     timer: 1000,
	                     buttons:false
	                  });
							that.bill.bill_no='';
							that.bill.delivery_date='';
							that.bill.customer_name='';
							that.bill.contact='';
							that.bill.total='';
							that.bill.advance='';
							that.bill.note='';

							// reset Error
							// focus on the bill
							$("#bill_no").focus();
	               })
	               .catch(function (error) {
	                  console.log(error);
	               });
	            }
            }
			}
		},
		computed:{
			due: function(){
           	if (this.bill.total-this.bill.advance > 0) {
           		return this.bill.total-this.bill.advance
           	}
			}
		}
	}
</script>