import { Injectable } from '@angular/core';

import { APPLICATION_CONSTANTS } from '../constants';
import { HttpClient, HttpHeaders } from '@angular/common/http';
@Injectable({
  providedIn: 'root'
})
export class OrderService {
  private baseUrl = APPLICATION_CONSTANTS.BASE_URL;
  private headers = null;

  constructor(private httpclient: HttpClient) {
    let headers = new HttpHeaders();
    headers = headers.set('content-type', 'application/json');
    this.headers = headers;
  }

  listOrders(type, name) {
    return this.httpclient.get(`${this.baseUrl}${APPLICATION_CONSTANTS.GET_ORDERS}`, {
      headers: this.headers,
      params: {
        'getby': type,
        'name': name
      }
    }).toPromise();
  }

  getOrderById(id) {
    return this.httpclient.get(`${this.baseUrl}${APPLICATION_CONSTANTS.GET_ORDERS}${id}`, {
      headers: this.headers,
      params: {
      }
    }).toPromise();
  }

  createOrder(data) {
    return this.httpclient.post(`${this.baseUrl}${APPLICATION_CONSTANTS.GET_ORDERS}`, data, {
      headers: this.headers,
    }).toPromise();
  }

}
