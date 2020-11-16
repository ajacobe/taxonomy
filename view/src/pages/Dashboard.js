import React, { Component } from 'react';
import { Container } from 'semantic-ui-react'
import ApiHandler from '../utils/APIHandler';
import HTree from '../components/HTree';
class Dashboard extends Component {

    
    render() {
        return (
			<Container style={{ marginTop: '3em' }}>
                <HTree  />
			</Container>
        );
    }
}

export default Dashboard;