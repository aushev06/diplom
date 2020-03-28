import React from 'react';
import {Switch, Route} from 'react-router-dom';
import Auth from './pages/Auth';
function App() {
    return (
        <div className="wrapper">

            <Switch>
                <Route
                    exact
                    path={["/", "/signup"]}
                    component={Auth}
                />
            </Switch>
        </div>
    );
}

export default App;
