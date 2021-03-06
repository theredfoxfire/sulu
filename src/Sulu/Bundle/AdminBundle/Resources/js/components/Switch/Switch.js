// @flow
import React from 'react';
import classNames from 'classnames';
import Icon from '../Icon/index';
import type {SwitchProps} from './types';
import switchStyles from './switch.scss';

type Props = SwitchProps & {
    className?: string,
    icon?: string,
    type: string,
    onChange?: (checked: boolean, value?: string | number) => void,
};

export default class Switch extends React.PureComponent<Props> {
    static defaultProps = {
        type: 'checkbox',
    };

    handleChange = (event: SyntheticEvent<HTMLInputElement>) => {
        if (this.props.onChange) {
            this.props.onChange(event.currentTarget.checked, this.props.value);
        }
    };

    render() {
        const {className, type, name, checked, icon, children} = this.props;
        const switchClass = classNames(
            switchStyles.switch,
            className
        );

        return (
            <label className={switchStyles.label}>
                <span className={switchClass}>
                    <input
                        type={type}
                        name={name}
                        checked={checked}
                        onChange={this.handleChange} />
                    <span>
                        {icon &&
                            <Icon name={icon} />
                        }
                    </span>
                </span>
                {children &&
                    <span>{children}</span>
                }
            </label>
        );
    }
}
