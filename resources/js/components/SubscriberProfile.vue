<template>
	<v-container>
		<v-card
			outlined
			min-width="450"
			style="height: 450px"
		>
			<v-toolbar color="#55A256" dark>
				<v-toolbar-title
					v-text="(editing ? 'Editing' : 'New') +  ' Subscriber'"
				></v-toolbar-title>
			</v-toolbar>
			<v-card-text>
				<v-col>
					<v-text-field
						filled
						outlined
						required
						label="Name*"
						v-model="subscriber.name"
					></v-text-field>
					<errors
						:errors="errors.name"
					></errors>

					<v-text-field
						filled
						outlined
						required
						label="Email*"
						v-model="subscriber.email"
					></v-text-field>
					<errors
						:errors="errors.email"
					></errors>

					<v-select
						filled
						outlined
						required
						label="State*"
						v-model="subscriber.state"
						:items="Object.keys(subscriberStates)"
					></v-select>
				</v-col>
			</v-card-text>
			<v-card-actions>
				<v-spacer></v-spacer>
				<v-btn
					v-show="editing"
					@click="finishEdit"
				>Cancel</v-btn>
				<v-btn
					dark
					color="#55A255"
					:disabled="saving"
					v-if="! editing"
					@click="createSubscriber"
				>Create</v-btn>
				<v-btn
					dark
					color=#55A255
					:disabled="saving"
					v-else
					@click="updateSubscriber"
				>Update</v-btn>
			</v-card-actions>
		</v-card>
		<subscriber-profile-additional-fields
			v-if="editing"
			:key="subscriber.id"
			:subscriber-id="subscriber.id"
			:subscriber-fields="subscriber.fields"
		></subscriber-profile-additional-fields>
	</v-container>
</template>
<script>
	export default {
		props: ['subscriberStates'],
		data: () => ({
			errors: [],
			saving: false,
			editing: false,
			activeIndex: null,
			subscriber: {
				name: '',
				email: '',
				state: 'unconfirmed',
				fields: []
			}
		}),
		methods: {
			createSubscriber() {
				this.saving = true

				axios.post('/api/subscribers', this.subscriber)
					.then(this.addSubscriber)
					.catch(this.showErrors)
					.then(this.saving = false)
			},
			addSubscriber({data}) {
				this.resetForm()

				data.fields = []

				this.$emit('addSubscriber', data)
			},
			editSubscriber(subscriber, index) {
				this.editing = true

				this.activeIndex = index

				this.errors = []

				this.subscriber = _.clone(subscriber, true)
			},
			updateSubscriber() {
				this.saving = true

				axios.put(`/api/subscribers/${this.subscriber.id}`, this.subscriber)
					.then(({data}) => this.changeSubscriber(data, this.activeIndex))
					.catch(this.showErrors)
					.then(this.saving = false)
			},
			changeSubscriber(subscriber, index) {
				this.finishEdit()

				this.$emit('changeSubscriber', {subscriber, index})
			},
			showErrors({response}) {
				this.errors = response.data.errors
			},
			finishEdit() {
				this.editing = false

				this.resetForm()
			},
			resetForm() {
				this.errors = []

				this.subscriber = {
					name: '',
					email: '',
					state: 'unconfirmed',
					fields: []
				}
			}
		}
	}
</script>
