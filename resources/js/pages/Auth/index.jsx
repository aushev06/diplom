import React from "react";
import {Route} from "react-router-dom";

import LoginFormContainer from "./components/Login/containers/LoginFormContainer";
import "./Auth.scss";

const Auth = () => (
    <section className="auth">
        <div className="auth__content">
            <Route exact path="/" component={LoginFormContainer}/>
        </div>
    </section>
);

export default Auth;