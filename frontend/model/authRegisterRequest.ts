/**
 * Generated by orval v7.8.0 🍺
 * Do not edit manually.
 * Tião Carreiro - Api
 * OpenAPI spec version: 0.0.1
 */

export interface AuthRegisterRequest {
  /** @maxLength 255 */
  name: string;
  /** @maxLength 255 */
  email: string;
  /** @minLength 8 */
  password: string;
}
