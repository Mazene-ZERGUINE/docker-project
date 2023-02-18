import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { ToastComponent } from './toast.component';
import { NgbToast } from '@ng-bootstrap/ng-bootstrap';

@NgModule({
  declarations: [ToastComponent],
  exports: [ToastComponent],
  imports: [CommonModule, NgbToast],
})
export class ToastModule {}
