<template>
    <div>
        <component
                :key="$i"
                v-for="(field, $i) in fields"
                :is="resolveComponentName(field)"
                :resource-name="resourceName"
                :resource-id="resourceId"
                :field="field"
                v-model="field.value"
                :errors="errors"
        />
    </div>
</template>

<script>
    import {Errors} from 'form-backend-validation'
    const root = 'resource-tool/metadata'

    export default {
        props: {
            resourceName: {},
            resourceId: {},
        },

        data() {
            return {
                fields: {},
                errors: new Errors(),
                loading: false,
                lastRetrievedAt: null,
            }
        },

        created() {
            this.fetch()

            App.$on('saveResource', () => {
                this.save()
            })
        },

        destroyed() {
            App.$off('saveResource')
        },

        methods: {
            resolveComponentName(field) {
                return field.prefixComponent ? 'form-' + field.component : field.component
            },

            fetch() {
                App.api.request({
                    url: root + '/' + this.resourceName + '/' + this.resourceId,
                }).then(data => {
                    this.fields = data

                    this.updateLastRetrievedAtTimestamp()
                })
            },

            /**
             * Update the last retrieved at timestamp to the current UNIX timestamp.
             */
            updateLastRetrievedAtTimestamp() {
                this.lastRetrievedAt = Math.floor(new Date().getTime() / 1000)
            },

            save() {
                this.loading = true

                App.api.request({
                    method: 'POST',
                    url: root + '/' + this.resourceName + '/' + this.resourceId,
                    data: this.updateFormData()
                }).then(() => {
                    this.loading = false

                    App.$emit('metadataUpdate', this.resourceId)

                    this.validationErrors = new Errors()

                    this.updateLastRetrievedAtTimestamp()
                }).catch(({data, status}) => {
                    this.loading = false

                    if (status == 422) {
                        this.validationErrors = new Errors(data.errors)
                    }

                    if (status == 409) {
                        this.$toasted.show(
                            this.__('Another user has updated metadata since this page was loaded. Please refresh the page and try again.'),
                            {type: 'error'}
                        )
                    }
                })
            },

            updateFormData() {
                return _.tap(new FormData(), formData => {
                    _(this.fields).each(field => {
                        if (field.fill)
                            field.fill(formData)
                    })

                    formData.append('_method', 'PUT')
                    formData.append('_retrieved_at', this.lastRetrievedAt)
                })
            },
        },
    }
</script>
