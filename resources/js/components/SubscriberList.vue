<template>
	<v-container>
		<v-card
			outlined
			min-width="450"
			style="overflow-y: auto; height: 450px"
		>
			<v-toolbar color="#55A256" dark>
				<v-toolbar-title>Subscriber List</v-toolbar-title>
			</v-toolbar>
			<v-card-text>
				<v-list>
					<template
						v-for="(subscriber, index) in subscribers"
					>
						<v-list-item>
							<v-list-item-content>
								<v-list-item-title
									v-text="subscriber.name"
								></v-list-item-title>
								<v-list-item-subtitle
									v-text="subscriber.email"
								></v-list-item-subtitle>
							</v-list-item-content>
							<v-list-item-content>
								<v-col>
									<v-chip
										dark
										:color="subscriberStates[subscriber.state]"
										v-text="subscriber.state"
									></v-chip>
								</v-col>
							</v-list-item-content>
							<v-list-item-icon>
				            	<v-btn
				            		dark
				            		small
				            		color="light-green"
				            		@click="editSubscriber(subscriber, index)"
			            		>
			            			<v-icon small>mdi-pencil</v-icon>
								</v-btn>
							</v-list-item-icon>
							<v-list-item-icon>
								<v-btn
				            		dark
				            		small
				            		color="red"
				            		@click="removeSubscriber(subscriber.id, index)"
				            		style="margin-left: 5px"
			            		>
									<v-icon small>mdi-delete</v-icon>
								</v-btn>
							</v-list-item-icon>
							<v-list-item-icon>
								<v-btn
				            		small
				            		@click="showSubscriberFields(subscriber)"
			            		>
									<v-icon small>mdi-eye</v-icon>
								</v-btn>
							</v-list-item-icon>
						</v-list-item>
			        	<v-divider
							v-if="index != (subscribers.length - 1)"
			        	></v-divider>
			    	</template>
				</v-list>
			</v-card-text>
		</v-card>
		<v-card outlined v-if="subscriber" class="mt10">
			<v-toolbar color="#55A256" dark>
				<v-toolbar-title>
					{{ subscriber.name }} additional fields
				</v-toolbar-title>
			</v-toolbar>
			<v-card-text>
			    <v-list-item
			    	:key="field.id"
			    	v-for="field in subscriber.fields"
		    	>
			      	<v-list-item-content>
			        	<v-list-item-subtitle>
			        		{{ `${field.title} (${field.type}): ${field.value}` }}
			        	</v-list-item-subtitle>
			      	</v-list-item-content>
			    </v-list-item>
			</v-card-text>
		</v-card>
	</v-container>
</template>
<script>
	export default {
		props: ['subscribers', 'subscriberStates'],
		data: () => ({
			subscriber: null
		}),
		methods: {
			editSubscriber(subscriber, index) {
				this.subscriber = null

				this.$emit('editSubscriber', {subscriber, index})
			},
			removeSubscriber(id, index) {
				axios.delete(`/api/subscribers/${id}`)
					.then(() => this.$emit('removeSubscriber', index))
			},
			showSubscriberFields(subscriber) {
				if(! this.subscriber || subscriber.id != this.subscriber.id) {
					return this.subscriber = subscriber
				}

				this.subscriber = null
			}
		}
	}
</script>
