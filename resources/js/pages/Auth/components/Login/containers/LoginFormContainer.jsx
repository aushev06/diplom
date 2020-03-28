import {withFormik} from 'formik';

import LoginForm from '../components/LoginForm';

import validateForm from '../../../../../utils/validate';
import {userApi} from "../../../../../core/api";

const LoginFormContainer = withFormik({
    enableReinitialize: true,
    mapPropsToValues: () => ({
        email: '',
        password: '',
    }),
    validate: values => {
        let errors = {};

        validateForm({isAuth: true, values, errors});

        return errors;
    },
    handleSubmit: (values, {setSubmitting, props}) => {
        userApi.login(values);
    },
    displayName: 'LoginForm',
})(LoginForm);

export default LoginFormContainer;
