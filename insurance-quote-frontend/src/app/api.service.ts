// src/app/api.service.ts
import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { environment } from '../environments/environment';
import { HttpHeaders } from '@angular/common/http';

export type Quote = {
  matched_coverage_amount: number;
  monthly_premium: string;
  quarterly_premium: string;
  semi_annual_premium: string;
  annual_premium: string;
};

@Injectable({
  providedIn: 'root',
})
export class ApiService {
  private apiUrl = environment.apiUrl;

  constructor(private http: HttpClient) {}

  // src/app/api.service.ts
  generateQuote(data: any) {
    return this.http.post(`${this.apiUrl}/generate-quote`, data, {
      headers: new HttpHeaders({
        'Content-Type': 'application/json',
        Accept: 'application/json',
      }),
    });
  }
}
