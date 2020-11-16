import React, { Component } from 'react';
import '../assets/style.css';
import Tree from 'react-d3-tree';
import ApiHandler from '../utils/APIHandler';
import AddChildModal from '../components/modal/AddChildModal';
import {
    Header,
    Button
} from 'semantic-ui-react';

const containerStyles = {
    width: '100%',
    height: '250vh',
}
export default class HTree extends Component 
{
    state = {
        translate: {},
        treeData: [],
        employees: [],
        roles: []
    }

    componentDidMount() {
        const dimensions = this.treeContainer.getBoundingClientRect();
        this.setState({
        translate: {
            x: dimensions.width / 2,
            y: dimensions.height / 10
        }
        });
        this.fetchOrganizationModel();
    }

    updateOrganizationModel = (treeData) => {
        // console.log(treeData);
        this.setState({treeData})
    }
    
    
    fetchOrganizationModel = () => {
		let apiHandler = new ApiHandler();
		let handle = this;
		apiHandler.push("/employees-network", "GET", null, function (response) {
			if (response.status == 200) {
				let data = response.data;
                let treeData = data['organization_model'];
				handle.setState({ treeData});
			}
		});
	}

    render() {
        let handle = this
        return (
            <>
            <Header as='h1' dividing>
                Organizational Chart
                <AddChildModal parent={this} />        
            </Header>
            <div style={containerStyles} ref={tc => (this.treeContainer = tc)}>
                { handle.state.treeData.length > 0 ? <Tree orientation="vertical" pathFunc="step" translate={handle.state.translate}  data={handle.state.treeData} /> : "" }
            </div>
            </>
        );
    }
}