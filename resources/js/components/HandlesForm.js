import {Errors, Form} from 'form-backend-validation'

export default {
    props: {
        resourceName: {},
        resourceId: {},
    },

    data() {
        let self = this
        return {
            loading: false,
            validationErrors: new Errors(),
            form: new Form({
                title: '',
                unit: '',
                slug: '',
                main: false,
                object_id: self.resourceId
            })
        }
    },

    methods: {
        formData(method) {
            return _.tap(new FormData(), formData => {
                for (let [field, value] of Object.entries(this.form.data())) {
                    if (value === undefined || value === null)
                        value = ''

                    formData.append(field, value)
                }

                formData.append('_method', method)
            })
        },
    },
}
