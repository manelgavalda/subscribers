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

    <v-card
      class="mx-auto mt30"
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
              <errors
                :errors="errors.title"
              ></errors>
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
            	<v-row center-content>
            		<v-col cols="10">
		              <v-checkbox
		              	v-if="field.type == 'boolean'"
		              	class="mx-2"
		              	:label="field.title"
		              	v-model="field.value"
		            	></v-checkbox>
		              <v-text-field
		                filled
		                required
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
            </v-col>
          </v-row>
        </v-container>
      </v-card-text>
      <v-snackbar
	      color="green"
	      :timeout="1500"
	      v-model="fieldUpdated"
	    >
	    	Field Updated
	    </v-snackbar>
    </v-card>
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
      fieldUpdated: false,
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
      showErrors(data) {
        this.errors = data.response.data.errors
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
      },
      createField(field) {
        this.saving = true

        field.subscriber_id = this.subscriber.id

        axios.post('/api/fields', field)
          .then(this.addField)
          .catch(this.showErrors)
          .then(() => this.saving = false)
      },
      addField({data}) {
        this.saving = false

        this.errors = []

        this.field = {
          title: '',
          type: 'string'
        }

        this.subscriber.fields.push(data)
      },
      updateField(field) {
        this.saving = true

        axios.put(`/api/fields/${field.id}`, field)
          .then(() => this.fieldUpdated = true)
          .catch(this.showErrors)
          .then(() => this.saving = false)
      },
      deleteField(id, index) {
        axios.delete(`/api/fields/${id}`)
          .then(this.subscriber.fields.splice(index, 1))
      }
		},
    filters: {
      capitalize: text => text.charAt(0).toUpperCase() + text.slice(1)
    }
	}
</script>
