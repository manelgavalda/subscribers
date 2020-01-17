<template>
  <v-container>
    <v-card
      class="mx-auto"
      max-width="1000"
    >
      <v-card-title>
        <span class="headline">Subscriber Profile</span>
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
              <p v-for="error in errors.name">
                <ul>
                  <li v-text="error"></li>
                </ul>
              </p>
            </v-col>
            <v-col cols="12">
              <v-text-field
                filled
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

    <v-card
      class="mx-auto"
      max-width="1000"
      v-if="editing"
    >
      <v-card-title>
        <span>Additional Fields</span>
      </v-card-title>
      <v-card-text>
        <v-container>
          <v-row>
            <v-col cols="6">
              <v-text-field
                filled
                required
                type="string"
                label="New Field"
                v-model="field.title"
              ></v-text-field>
            </v-col>
            <v-col cols="6">
              <v-select
                filled
                required
                label="Type"
                append-outer-icon="mdi-plus"
                v-model="field.type"
                :items="['date', 'number', 'string', 'boolean']"
                @click:append-outer="createField(field)"
              ></v-select>
            </v-col>
            <v-col
              cols="12"
              v-for="(field, index) in subscriber.fields"
              :key="index"
            >
              <v-text-field
                filled
                required
                type="string"
                v-model="field.value"
                append-icon="mdi-content-save"
                append-outer-icon="mdi-delete"
                :label="field.title + ' (' + field.type + ')' | capitalize"
                @click:append="updateField(field)"
                @click:append-outer="deleteField(field.id, index)"
              ></v-text-field>
            </v-col>
          </v-row>
        </v-container>
      </v-card-text>
    </v-card>

    <v-card
      style="margin-top:40px"
      class="mx-auto"
      max-width="1000"
    >
      <v-toolbar color="#55A256" dark>
        <v-toolbar-title>Subscribers</v-toolbar-title>
      </v-toolbar>

      <v-list subheader>
        <v-list-item
          v-for="(subscriber, index) in subscribers"
          :key="index"
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
        state: 'unconfirmed',
        fields: []
      },
      field: {
        title: '',
        type: 'string'
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
          state: 'unconfirmed',
          fields: []
        }

        this.field = {
          title: '',
          type: 'string'
        }
      },
      createField(field) {
        this.saving = true

        field.subscriber_id = this.subscriber.id

        axios.post('/api/fields', field)
          .then(this.addField)
          .catch(this.showErrors)
      },
      addField({data}) {
        this.saving = false

        this.field = {
          title: '',
          type: 'string'
        }

        this.subscriber.fields.push(data)
      },
      updateField(field) {
        this.saving = true

        axios.put(`/api/fields/${field.id}`, field)
          .then(this.saving = false)
      },
      deleteField(id, index) {
        axios.delete(`/api/fields/${id}`)
          .then(this.subscriber.fields.splice(index, 1))
      },
    },
    filters: {
      capitalize: text => text.charAt(0).toUpperCase() + text.slice(1)
    }
  }
</script>
