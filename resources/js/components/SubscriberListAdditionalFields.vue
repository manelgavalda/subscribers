<template>
    <v-card outlined v-if="subscriber" class="mt10">
        <v-toolbar color="#55A256" dark>
            <v-toolbar-title>
                {{ subscriber.name }} additional fields
            </v-toolbar-title>
        </v-toolbar>
        <v-card-text>
            <v-list>
                <template
                    v-for="(field, index) in subscriber.fields"
                >
                    <v-list-item>
                        <v-list-item-content>
                            <v-list-item-title>
                                {{ field.title | capitalize}}
                            </v-list-item-title>
                            <v-list-item-subtitle
                                v-text="field.type"
                            ></v-list-item-subtitle>
                        </v-list-item-content>
                        <v-list-item-content>
                            <v-list-item-title>
                                <b>{{ field.value }}</b>
                            </v-list-item-title>
                        </v-list-item-content>
                    </v-list-item>
                    <v-divider
                        v-if="index != (subscriber.fields.length - 1)"
                    ></v-divider>
                </template>
            </v-list>
        </v-card-text>
    </v-card>
</template>
<script>
    export default {
        data: () => ({
            subscriber: null
        }),
        methods: {
            show(subscriber) {
                if(! this.subscriber || subscriber.id != this.subscriber.id) {
                    return this.subscriber = subscriber
                }

                this.subscriber = null
            }
        }
    }
</script>
