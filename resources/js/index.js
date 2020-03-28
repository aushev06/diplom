import React from 'react';
import ReactDOM from 'react-dom';
import './styles/index.scss';
import App from './Main';
import {BrowserRouter as Router} from "react-router-dom";


ReactDOM.render(
    <Router>
        <App/>
    </Router>
    ,
    document.getElementById('root')
);

