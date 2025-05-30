// src/app/quote-form/quote-form.component.ts
import { Component } from '@angular/core';
import { FormBuilder, FormGroup, Validators } from '@angular/forms';
import { ApiService } from '../api.service';
import { CommonModule } from '@angular/common';
import { FormsModule, ReactiveFormsModule } from '@angular/forms';

@Component({
  selector: 'app-quote-form',
  templateUrl: './quote-form.component.html',
  styleUrls: ['./quote-form.component.scss'],
  standalone: true,
  imports: [CommonModule, FormsModule, ReactiveFormsModule],
})
export class QuoteFormComponent {
  quoteForm: FormGroup;
  quoteResult: any = null;
  loading = false;
  error = '';
  inputClasses =
    'shadow border w-xs rounded py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline';
  selectClasses =
    'shadow border w-86 rounded py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline';
  labelClasses = 'block text-sm font-bold my-2';
  buttonClasses =
    'mt-5 border rounded-md px-3 py-1.5 font-medium hover:bg-gray-200 active:bg-gray-300';
  states = [
    'AL',
    'AK',
    'AZ',
    'AR',
    'CA',
    'CO',
    'CT',
    'DE',
    'FL',
    'GA',
    'HI',
    'ID',
    'IL',
    'IN',
    'IA',
    'KS',
    'KY',
    'LA',
    'ME',
    'MD',
    'MA',
    'MI',
    'MN',
    'MS',
    'MO',
    'MT',
    'NE',
    'NV',
    'NH',
    'NJ',
    'NM',
    'NY',
    'NC',
    'ND',
    'OH',
    'OK',
    'OR',
    'PA',
    'RI',
    'SC',
    'SD',
    'TN',
    'TX',
    'UT',
    'VT',
    'VA',
    'WA',
    'WV',
    'WI',
    'WY',
  ];

  constructor(private fb: FormBuilder, private api: ApiService) {
    this.quoteForm = this.fb.group({
      dob: ['', Validators.required],
      state: ['', [Validators.required, Validators.maxLength(2)]],
      smoker: [false, Validators.required],
      gender: ['M', Validators.required],
      term: ['', Validators.required],
      coverage_amount: [
        100000,
        [Validators.required, Validators.min(100000), Validators.max(1000000)],
      ],
    });
  }

  onSubmit() {
    if (this.quoteForm.valid) {
      this.loading = true;
      this.error = '';
      this.quoteResult = null;

      this.api.generateQuote(this.quoteForm.value).subscribe({
        next: (result) => {
          this.quoteResult = result;
          this.loading = false;
        },
        error: (err) => {
          this.error = err.error?.error || 'An error occurred';
          this.loading = false;
        },
      });
    }
  }
}
