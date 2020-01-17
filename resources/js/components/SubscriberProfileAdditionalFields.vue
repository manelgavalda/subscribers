<template>
	<v-card
		class="mx-auto mt10"
		max-width="595"
	>
		<v-toolbar color="#55A256" dark>
			<v-toolbar-title>Additional Fields</v-toolbar-title>
		</v-toolbar>
		<v-card-text>
			<v-container>
				<v-row>
					<v-col cols="6">
						<v-text-field
							filled
							shaped
							required
							label="New Field"
							v-model="field.title"
						></v-text-field>
					</v-col>
					<v-col cols="6">
						<v-select
							filled
							shaped
							required
							label="Type"
							append-outer-icon="mdi-plus"
							:items="['date', 'number', 'string', 'boolean']"
							v-model="field.type"
							@click:append-outer="createField(field)"
						></v-select>
					</v-col>
					<v-row>
						<v-col
							cols="12"
							v-for="(field, index) in fields"
							:key="index"
						>
							<v-card>
								<v-row>
									<v-col cols="10">
										<v-checkbox
											outlined
											class="mx-5"
											:label="field.title"
											v-if="field.type == 'boolean'"
											v-model.number="field.value"
										></v-checkbox>
										<v-text-field
											shaped
											outlined
											required
											class="mx-5"
											:type="field.type"
											:label="field.title | capitalize"
											v-else
											v-model="field.value"
										></v-text-field>
									</v-col>
									<v-col cols="2">
										<v-icon @click="updateField(field, index)">
											mdi-content-save
										</v-icon>
										<v-icon @click="deleteField(field.id, index)">
											mdi-delete
										</v-icon>
									</v-col>
								</v-row>
							</v-card>
						</v-col>
					</v-row>
				</v-row>
			</v-container>
		</v-card-text>
		<v-snackbar
			:color="notification.color"
			:timeout="1500"
			v-model="notification.status"
		>{{ notification.text }}</v-snackbar>
	</v-card>
</template>
<script>
	export default {
		props: ['subscriberId', 'subscriberFields'],
		data: () => ({
			fields: [],
			notification: {},
			field: {
				title: '',
				type: 'string',
				subscriber_id: null
			}
		}),
		mounted() {
			this.fields = this.subscriberFields
		},
		methods: {
			createField(field) {
				this.saving = true

				field.subscriber_id = this.subscriberId

				axios.post('/api/fields', field)
					.then(this.addField)
					.catch(this.showErrorNotification)
					.then(this.saving = false)
			},
			addField({data}) {
				this.saving = false

				this.sendNotification('green', 'success')

				this.field = {
					title: '',
					type: 'string'
				}

				this.fields.push(data)
			},
			updateField(field) {
				this.saving = true

				axios.put(`/api/fields/${field.id}`, field)
					.then(this.changeField)
					.catch(this.showErrorNotification)
					.then(this.saving = false)
			},
			changeField() {
				this.fieldUpdated = true

				this.sendNotification('green', 'success')
			},
			deleteField(id, index) {
				axios.delete(`/api/fields/${id}`)
					.then(this.fields.splice(index, 1))
			},
			showErrorNotification() {
				this.sendNotification('red', 'Error')
			},
			sendNotification(color, text) {
				this.notification = {
					color,
					text,
					status: true
				}
			}
		},
		filters: {
			capitalize: text => text.charAt(0).toUpperCase() + text.slice(1)
		}
	}
</script>
<style>
	.mt10 {
		margin-top: 10px
	}
</style>
