import { ComponentFixture, TestBed } from '@angular/core/testing';

import { MomoUiComponent } from './momo-ui.component';

describe('MomoUiComponent', () => {
  let component: MomoUiComponent;
  let fixture: ComponentFixture<MomoUiComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ MomoUiComponent ]
    })
    .compileComponents();

    fixture = TestBed.createComponent(MomoUiComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
