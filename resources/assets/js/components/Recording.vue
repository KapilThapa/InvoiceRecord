<template>
	<div>
		<div class="row">
			<div class="col-md-3">
				<div class="card custom-card">
					<div class="card-body">
						<BillRecord></BillRecord>
					</div>
				</div>
			</div>
		</div>
	</div>
</template>

<script>
	import BillRecord from './Modules/BillRecord.vue'
	export default {
		components:{
			BillRecord
		}
	}
</script>

