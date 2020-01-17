<template>
  <v-card
    class="mx-auto mt30"
    max-width="600"
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
            v-for="(field, index) in fields"
            :key="index"
          >
          	<v-card>
            	<v-row center-content>
            		<v-col cols="10">
		              <v-checkbox
		              	v-if="field.type == 'boolean'"
		              	class="mx-2"
		              	:label="field.title"
		              	v-model="field.value"
		            	></v-checkbox>
		              <v-text-field
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
          	</v-card>
          </v-col>
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
			snackbar: false,
			fields: {},
      field: {
        title: '',
        type: 'string'
      },
      notification: {
      	color: 'green',
      	text: 'Field updated correctly',
      	status: false
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
          .catch(this.showErrors)
          .then(() => this.saving = false)
      },
      addField({data}) {
        this.saving = false

        this.notification = {
        	color: 'green',
        	text: 'Success',
        	status: true
        }

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
          .catch(this.showErrors)
          .then(() => this.saving = false)
      },
      changeField() {
      	this.fieldUpdated = true

        this.notification = {
        	color: 'green',
        	text: 'Success',
        	status: true
        }
      },
      deleteField(id, index) {
        axios.delete(`/api/fields/${id}`)
          .then(this.fields.splice(index, 1))
      },
      showErrors(data) {
      	this.notification = {
      		color: 'red',
      		text: 'Error',
        	status: true
      	}
      },
		},
    filters: {
      capitalize: text => text.charAt(0).toUpperCase() + text.slice(1)
    }
	}
</script>
