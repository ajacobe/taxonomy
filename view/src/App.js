import React from 'react';
import Dashboard from './pages/Dashboard';
import { BrowserRouter, Redirect, Route, Switch } from 'react-router-dom';

function App() {
  return (
    <div>
      
      <BrowserRouter basename={process.env.REACT_APP_BASENAME}>
        <Switch>
          <Route exact path="/" component={Dashboard} />
        </Switch>
      </BrowserRouter>
    </div>
  )
}

export default App;
