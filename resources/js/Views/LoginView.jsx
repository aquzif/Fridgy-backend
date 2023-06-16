import {useFormik} from "formik";
import LoginFormSchema from "@/Schemas/LoginFormSchema";
import {useState} from "react";
import AuthAPI from "@/API/AuthAPI";
import toast from "react-hot-toast";
import store from "@/Store/store";
import {login} from "@/Store/Reducers/AuthReducer";


const LoginView = () => {

    const [isLogining, setIsLogining] = useState(false);

    const tryLogin = async (values) => {
        let result = await AuthAPI.login(values.email,values.password);

        if(result.status === 401) {
            throw new Error('Invalid credentials');
        }

        store.dispatch(login(result.data));

    }

    const formik = useFormik({
        initialValues: {
            email: '',
            password: '',
        },
        validationSchema: LoginFormSchema,
        onSubmit: async values => {
            setIsLogining(true);

            await toast.promise(tryLogin(values), {
                loading: 'Logowanie...',
                success: 'Zalogowano pomyślnie',
                error: 'Błędne dane logowania',
            }).catch((err) => {
                console.log(err);
                setIsLogining(false);
            });


        }
    });


    return (
        <>
            <div className="bg-img">
                <form onSubmit={formik.handleSubmit}>
                    <input
                        type={'text'}
                        name={'email'}
                        placeholder={'email'}
                        onChange={formik.handleChange}
                        onBlur={formik.handleBlur}
                        value={formik.values.email}
                    /> <br/>
                    <span>{formik.touched.email && formik.errors.email}</span> <br/>
                    <input
                        type={'password'}
                        name={'password'}
                        placeholder={'Password'}
                        onChange={formik.handleChange}
                        onBlur={formik.handleBlur}
                        value={formik.values.password}
                    /> <br/>
                    <span>{formik.touched.password && formik.errors.password}</span> <br/>
                    <button disabled={isLogining} type="submit" >Login</button>
                </form>
            </div>
        </>
    )

}

export default LoginView;
