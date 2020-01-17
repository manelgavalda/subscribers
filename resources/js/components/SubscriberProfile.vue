<template>
	<v-container>
		<v-card
      class="mx-auto"
      max-width="600"
    >
      <v-card-title>
        <span
        	class="headline"
        	v-text="(editing ? 'Editing' : 'New') +  ' Subscriber'">
      	</span>
      </v-card-title>
      <v-card-text>
        <v-container>
          <v-row>
            <v-col cols="12">
              <v-text-field
                filled
                required
                label="Name*"
                v-model="subscriber.name"
              ></v-text-field>
              <errors
                :errors="errors.name"
              ></errors>
            </v-col>
            <v-col cols="12">
              <v-text-field
                filled
                required
                label="Email*"
                v-model="subscriber.email"
              ></v-text-field>
              <errors
                :errors="errors.email"
              ></errors>
            </v-col>
            <v-col cols="12">
              <v-select
                filled
                required
                label="State*"
                v-model="subscriber.state"
                :items="Object.keys(subscriberStates)"
              ></v-select>
            </v-col>
          </v-row>
        </v-container>
      </v-card-text>
      <v-card-actions>
        <v-spacer></v-spacer>
        <v-btn
          text
          color="blue darken-1"
          v-show="editing"
          @click="finishEdit"
        >Cancel</v-btn>
        <v-btn
          text
          color="#55A255"
          v-if="! editing"
          @click="createSubscriber"
          :disabled="saving"
        >Create</v-btn>
        <v-btn
          text
          color="#55A255"
          v-else
          @click="updateSubscriber"
          :disabled="saving"
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
          .then(() => this.saving = false)
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
          .then(() => this.saving = false)
      },
      changeSubscriber(subscriber, index) {
        this.finishEdit()

        this.$emit('changeSubscriber', {subscriber, index})
      },
      showErrors({response}) {
        this.errors = response.data.errors
      },
      resetForm() {
        this.errors = []

        this.subscriber = {
          name: '',
          email: '',
          state: 'unconfirmed',
          fields: []
        }

        this.field = {
          title: '',
          type: 'string'
        }
      },
      finishEdit() {
        this.editing = false

        this.resetForm()
      }
		}
	}
</script>
