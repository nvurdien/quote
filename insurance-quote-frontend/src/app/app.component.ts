// src/app/app.component.ts
import { Component } from '@angular/core';
import { CommonModule } from '@angular/common';
import { QuoteFormComponent } from './quote-form/quote-form.component';

@Component({
  selector: 'app-root',
  standalone: true,
  imports: [CommonModule, QuoteFormComponent],
  templateUrl: './app.component.html',
  styleUrls: ['./app.component.scss'],
})
export class AppComponent {
  title = 'insurance-quote-frontend';
}
