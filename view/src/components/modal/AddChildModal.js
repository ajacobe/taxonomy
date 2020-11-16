import React, { Component } from 'react';
import ApiHandler from '../../utils/APIHandler';

import {
    Button,
    ButtonGroup,
    Row,
    Col,
    Spinner,
    Form,
    Modal, Input
} from 'semantic-ui-react';
class AddChildModal extends Component {
    state = {
        employee: {},
        employees: [],
        departments: [],
        roles: [],
        position: "Developer",
        submitLbl: "Save",
        open: false
    }

    setOpen = (status) => {
        console.log(status)
        this.setState( {open: status} );
    }

    componentDidMount() {
        this.fetchEmployeeFormData();
    }

    fetchEmployeeFormData = () => {
		let apiHandler = new ApiHandler();
		let handle = this;
		apiHandler.push("/employee-form-data", "GET", null, function (response) {
			if (response.status == 200) {
				let data = response.data;
                let roles = data['roles'];
                let employees = data['employees'];
                let departments = data['departments'];
                let employee = handle.state.employee
                if(employees.length > 0){
                    let defaultSupervisor = employees[0]
                    employee.ParentId = defaultSupervisor.Id
                }
                if(roles.length > 0){
                    let defaultRole = roles[0]
                    employee.RoleId = defaultRole.Id
                }

                if(departments.length > 0){
                    let defaultDepartment = departments[0]
                    employee.DepartmentId = defaultDepartment.Id
                }
                
                handle.setState({roles, employees, departments, employee});
            }
		});
	}
    
    handleSubmit = () => {
        var handle = this
        handle.setState({submitLbl: 'Saving...'})
        var apiHandler = new ApiHandler();
        apiHandler.push("/create-employee", 
                "POST",
                {employee: handle.state.employee} ,
                function(response) {
                    if(response.status == 200) {
                        handle.props.parent.updateOrganizationModel(response.data.organization_model)
                        handle.setState({submitLbl: 'Submit'})
                        handle.setOpen(false)
                    }
                }
            );

    }

    

    handleInputChange = (e) => {
        var handle = this
        var name = e.target.name
        let employee = handle.state.employee
        employee[name] = e.target.value
        handle.setState({employee});
        if(name == "RoleId"){
            let index = e.target.selectedIndex;
            let position = e.target[index].text
            handle.setState({position});
        }
    }
    render() {
        let handle = this
        return (
            <Modal
            size="small"
            onClose={() => this.setOpen(false)}
            onOpen={() => this.setOpen(true)}
            open={this.state.open}
            trigger={<Button className="float-right primary">Add</Button>}
            >
                <Modal.Header>Add New Employee</Modal.Header>
                <Modal.Content>
                    <Form>
                        <Form.Field>
                            <label>First Name</label>
                            <input name="FirstName" onChange={this.handleInputChange} required placeholder='First Name' />
                        </Form.Field>
                        <Form.Field>
                            <label>Last Name</label>
                            <input name="LastName" onChange={this.handleInputChange} required placeholder='Last Name' />
                        </Form.Field>
                        <Form.Field label='Supervisor' onChange={this.handleInputChange} required name="ParentId" control='select'>
                            {
                                handle.state.employees.map(item => (
                                    <option key={item.Id} value={item.Id}>{item.FirstName} {item.LastName}</option>
                                ))
                            }
                        </Form.Field>
                        <Form.Field label='Roles' name="RoleId" onChange={this.handleInputChange} required control='select'>
                            {
                                handle.state.roles.map(item => (
                                    <option key={item.Id} value={item.Id}>{item.Name}</option>
                                ))
                            }
                        </Form.Field>
                        {
                            handle.state.position == "Manager" ?
                            <Form.Field label='Department' name="DepartmentId" onChange={this.handleInputChange} control='select'>
                            {
                                handle.state.departments.map(item => (
                                    <option key={item.Id} value={item.Id}>{item.Name}</option>
                                ))
                            }
                            </Form.Field>
                            : ""
                        }
                        {
                            handle.state.position == "Developer" ?
                            <Form.Field>
                                <label>Language</label>
                                <input name="Language" onChange={this.handleInputChange} placeholder='Language' />
                            </Form.Field>
                            :""
                        }
                    </Form>
                </Modal.Content>
                <Modal.Actions>
                    <Button negative onClick={() => this.setOpen(false)}>
                        Cancel
                    </Button>
                    <Button positive onClick={this.handleSubmit}>
                        {handle.state.submitLbl}
                    </Button>
                </Modal.Actions>
            </Modal>
        );
    }
}

export default AddChildModal;