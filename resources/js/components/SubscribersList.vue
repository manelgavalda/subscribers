<template>
  <v-container>
    <v-card>
      <v-card-title>
        <span class="headline">Subscriber Profile</span>
      </v-card-title>
      <v-card-text>
        <v-container>
          <v-row>
            <v-col cols="12">
              <v-text-field
                required
                label="Name*"
                v-model="subscriber.name"
              ></v-text-field>
              <p v-for="error in errors.name">
                <ul>
                  <li v-text="error"></li>
                </ul>
              </p>
            </v-col>
            <v-col cols="12">
              <v-text-field
                required
                label="Email*"
                v-model="subscriber.email"
              ></v-text-field>
              <p v-for="error in errors.email">
                <ul>
                  <li v-text="error"></li>
                </ul>
              </p>
            </v-col>
            </v-col>
            <v-col cols="12">
              <v-select
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
    <v-card>
      <v-toolbar color="#55A256" dark>
        <v-toolbar-title>Subscribers</v-toolbar-title>
      </v-toolbar>

      <v-list subheader>
        <v-list-item
          v-for="(subscriber, index) in subscribers"
          :key="subscriber.id"
        >
          <v-list-item-content>
            <v-list-item-title
              v-text="subscriber.name"
            ></v-list-item-title>
            <v-list-item-subtitle
              v-text="subscriber.email"
            ></v-list-item-subtitle>
          </v-list-item-content>
          <v-list-item-content>
            <v-chip
              :color="subscriberStates[subscriber.state]"
              v-text="subscriber.state"
            ></v-chip>
          </v-list-item-content>
          <v-list-item-icon>
            <v-icon
            	@click="editSubscriber(subscriber, index)"
          	>mdi-pencil</v-icon>
            <v-icon
            	@click="removeSubscriber(subscriber.id, index)"
          	>mdi-delete</v-icon>
          </v-list-item-icon>
        </v-list-item>
      </v-list>
    </v-card>
  </v-container>
</template>
<script>
  export default {
    props: ['subscribers'],
    data: () => ({
      errors: [],
      saving: false,
      editing: false,
      activeIndex: null,
      subscriberStates: {
        active: 'green',
        unsubscribed: 'secondary',
        junk: 'red',
        bounced: 'primary',
        unconfirmed: ''
      },
      subscriber: {
        name: '',
        email: '',
        state: 'unconfirmed'
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

        this.$emit('addSubscriber', data)
      },
      editSubscriber(subscriber, index) {
        this.editing = true

        this.activeIndex = index
        this.subscriber = subscriber
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
    	removeSubscriber(id, index) {
    		axios.delete(`/api/subscribers/${id}`)
    			.then(this.$emit('removeSubscriber', index))
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
          state: 'unconfirmed'
        }
      }
    }
  }
</script>
