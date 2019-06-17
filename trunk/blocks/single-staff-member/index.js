/**
 * Block dependencies
 */
import './style.scss';
import './editor.scss';

/**
 * Custom components
 */
import PostSelector from '../components/PostSelector';

/**
 * Internal block libraries
 */
const { __ } = wp.i18n;
const { registerBlockType } = wp.blocks;
const { InspectorControls } = wp.editor;
const { decodeEntities } = wp.htmlEntities;
const { apiFetch } = wp;
const {
    PanelBody,
    PanelRow,
    SelectControl,
    ToggleControl
} = wp.components;

const renderAdminStaffMember = props => {
    const { id, layout } = props.attributes;

    if ( ! id )
        return <p>Please select a Staff Member.</p>;

    return <p>Staff ID: {id}, Layout: {layout}</p>;
}

/**
 * Register block
 */
export default registerBlockType(
    'simple-staff-list/single-staff-member',
    {
        title: __( 'Single Staff Member', 'simple-staff-list' ),
        description: __( 'Show a single staff member block.', 'simple-staff-list' ),
        category: 'common',
        icon: 'groups',
        keywords: [
            __( 'simple staff member', 'simple-staff-list' ),
            __( 'simple single', 'simple-staff-list' ),
            __( 'staff list', 'simple-staff-list' ),
        ],
        attributes: {
            id: {
                type: 'number',
            },
            layout: {
                type: 'string',
                // TODO: This needs to be dynamic
                default: 'sslp-left-1'
            },
            content: {
                type: 'array',
                // TODO: These need to be dynamic
                default: [
                    {
                        name: 'image',
                        label: __('Staff Photo', 'simple-staff-list'),
                        value: true,
                    },
                    {
                        name: 'name',
                        label: __('Name', 'simple-staff-list'),
                        value: true,
                    },
                    {
                        name: 'bio',
                        label: __('Bio', 'simple-staff-list'),
                        value: true,
                    },
                    {
                        name: 'position',
                        label: __('Position', 'simple-staff-list'),
                        value: false,
                    },
                    {
                        name: 'email',
                        label: __('Email', 'simple-staff-list'),
                        value: true,
                    },
                    {
                        name: 'phone',
                        label: __('Phone', 'simple-staff-list'),
                        value: true,
                    },
                    {
                        name: 'fb',
                        label: __('Facebook', 'simple-staff-list'),
                        value: true,
                    },
                    {
                        name: 'tw',
                        label: __('Twitter', 'simple-staff-list'),
                        value: true,
                    },
                ]
            }
        },
        edit: props => {
          const { attributes: { id, layout, content },
          className, setAttributes } = props;

          console.log('content', content);

          // Fetch the staff data if we have a staff ID but no staffData. This covers the scenario of opening a post with an existing Single Staff Member block.
          if ( props.attributes.id && ! props.attributes.staffData ) {
            apiFetch({
              path: `/wp/v2/staff-member/${id}?_embed`
            }).then(response => {
              const fullpost = {
                title: decodeEntities(response.title.rendered),
                id: response.id,
                excerpt: decodeEntities(response.excerpt.rendered),
                url: response.link,
                date: response.date,
                type: response.type,
                status: response.status,
                media: response._embedded && response._embedded['wp:featuredmedia'] ? response._embedded['wp:featuredmedia'][0] : null
              };
              // send data to the block;
              setAttributes({
                staffData: fullpost
              });
            });
          }

          return (
            <div className={ className }>
              <InspectorControls>
                <PanelBody>
                    <PanelRow>
                        <PostSelector
                        onPostSelect={post => {
                            setAttributes({
                            staffData: post,
                            id: post.id,
                            });
                        }}
                        inputPlaceholder='Type to search Staff Members'
                        hideInputOnLimit={true}
                        posts={ props.attributes.staffData ? [props.attributes.staffData] : [] }
                        onChange={newValue => {
                            const newStaffData = newValue.length === 0 ? undefined : newValue;
                            setAttributes({
                            staffData: newStaffData,
                            id: newValue.id,
                            });
                        }}
                        postType={'staff-member'}
                        limit="1"
                        />
                    </PanelRow>
                    <PanelRow>
                        <SelectControl
                            label={__("Choose Layout", "simple-staff-list")}
                            value={layout}
                            options={[
                            { value: "il-cr", label: __("Image Left, Content Right", "simple-staff-list") },
                            { value: "cl-ir", label: __("Content Left, Image Right", "simple-staff-list") },
                            { value: "it-cb", label: __("Image Top, Content Bottom", "simple-staff-list") }
                            ]}
                            onChange={layout => setAttributes({ layout })}
                        />
                    </PanelRow>
                </PanelBody>
                <PanelBody
                    title={__("Content Options", "simple-staff-list")}
                    initialOpen={false}
                >
                    <p>Turn specific fields on or off.</p>
                    {content.map( (option, i) => {
                        console.log(option);
                        return(
                            <ToggleControl
                                label={__(option.label, "simple-staff-list")}
                                checked={content[i].value}
                                onChange={newValue => {
                                    content[i].value = newValue;
                                    setAttributes({ 
                                        content: content
                                    })
                                }}
                            />
                        )
                    })}
                </PanelBody>
              </InspectorControls>
              <div className={`sslp-layout-${props.attributes.layout}`}>
                {
                  renderAdminStaffMember(props)
                }
              </div>
            </div>
          );
        },
        save: props => {
          return (
            <div className={`sslp-layout-${props.attributes.layout}`}>
                {
                renderAdminStaffMember(props)
                }
            </div>
          )
        },
    },
);
