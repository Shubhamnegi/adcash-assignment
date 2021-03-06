import { Injectable } from '@angular/core';
import { APPLICATION_CONSTANTS } from '../constants';
import { HttpClient, HttpHeaders } from '@angular/common/http';

@Injectable({
  providedIn: 'root'
})
export class UserService {

  private baseUrl = APPLICATION_CONSTANTS.BASE_URL;
  private headers = null;

  constructor(private httpclient: HttpClient) {
    let headers = new HttpHeaders();
    headers = headers.set('content-type', 'application/json');
    this.headers = headers;
  }

  listUsers() {
    return this.httpclient.get(`${this.baseUrl}${APPLICATION_CONSTANTS.GET_USER}`, {
      headers: this.headers,
      params: {
      }
    }).toPromise();
  }
}