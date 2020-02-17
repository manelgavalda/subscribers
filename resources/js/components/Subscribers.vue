<template>
	<v-app>
		<v-app-bar
			app
			dark
			color="#55A255"
		>
			<v-toolbar-title><b>Subscribers</b></v-toolbar-title>

			<v-spacer></v-spacer>

			<v-app-bar-nav-icon />
		</v-app-bar>

		<v-content>
			<v-container
				fluid
				class="fill-height"
			>
				<v-row>
					<v-col xs="12" md="6">
						<subscriber-list
							:subscribers="subscribers"
							:subscriber-states="subscriberStates"
							@editSubscriber="editSubscriber"
							@removeSubscriber="removeSubscriber"
						></subscriber-list>
					</v-col>
					<v-col xs="12" md="6">
						<subscriber-profile
							ref="subscriberProfile"
							:subscriber-states="subscriberStates"
							@addSubscriber="addSubscriber"
							@changeSubscriber="changeSubscriber"
						></subscriber-profile>
					</v-col>
				</v-row>
			</v-container>
		</v-content>
	</v-app>
</template>

<script>
	export default {
		data: () => ({
			subscribers: [],
			subscriberStates: {
				active: 'green',
				unsubscribed: 'secondary',
				junk: 'red',
				bounced: 'primary',
				unconfirmed: ''
			}
		}),
		mounted() {
			this.getSubscribers()
		},
		methods: {
			getSubscribers() {
				axios.get('/api/subscribers')
					.then(({data}) => this.subscribers = data)
			},
			addSubscriber(subscriber) {
				this.subscribers.push(subscriber)
			},
			changeSubscriber(data) {
				Vue.set(this.subscribers, data.index, data.subscriber)
			},
			removeSubscriber(index) {
				this.subscribers.splice(index, 1)
				this.$refs.subscriberProfile.finishEdit()
			},
			editSubscriber(data) {
				this.$refs.subscriberProfile
					.editSubscriber(data.subscriber, data.index)
			}
		},
		filters: {
			capitalize: text => text.charAt(0).toUpperCase() + text.slice(1)
		}
	}
</script>
<style>
	.mt30 {
		margin-top: 30px
	}
</style>
