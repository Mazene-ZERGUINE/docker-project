import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { HeaderModule } from './header/header.module';
import { FooterModule } from './footer/footer.module';
import { HeaderComponent } from './header/header.component';
import { FooterComponent } from './footer/footer.component';
import { NotFoundModule } from './not-found/not-found.module';
import { NotFoundComponent } from './not-found/not-found.component';

@NgModule({
  declarations: [],
  imports: [CommonModule, HeaderModule, FooterModule, NotFoundModule],
  exports: [HeaderComponent, FooterComponent, NotFoundComponent],
})
export class ComponentsModule {}
