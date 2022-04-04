import axios from "axios";
import React, { Component } from "react";
import { render } from "react-dom";
import Authenticated from "../Layouts/Authenticated";

interface Props {
  auth: any;
}

export default class Dashboard extends Component<Props> {
  componentDidMount() {}
  render() {
    return (
      <Authenticated
        auth={this.props.auth}
        header={
          <h2 className="text-xl font-semibold leading-tight text-gray-800">
            Dashboard
          </h2>
        }
      >
        <div className="py-12">
          <div className="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <span className="pl-5 font-bold">Add Menu Item</span>
            <div className="overflow-hidden bg-white shadow-sm sm:rounded-lg">
              <div className="p-6 bg-white border-b border-gray-200">
                You're logged in!
              </div>
            </div>
          </div>
        </div>
      </Authenticated>
    );
  }
}
